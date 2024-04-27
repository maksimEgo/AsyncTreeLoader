<?php

namespace App\Model\Processor;

use Swoole\Coroutine as co;
use Swoole\Coroutine\Channel;

class ParallelTaskProcessor
{
    private int $numThreads;
    private Channel $channel;

    // инициализируем количество потоков и канал через конструктор
    public function __construct(int $numThreads)
    {
        $this->numThreads = $numThreads;
        $this->channel = new Channel($numThreads);
    }

    public function process($tasks, callable $fn): \Generator
    {
        $coroutines = [];
        $nextCoroutineKey = 0; // Счетчик для уникальных ключей каждой корутины

        foreach ($tasks as $task) {
            // Если число активных корутин достигло максимума, ждем освобождения места
            while (count($coroutines) >= $this->numThreads) {
                $result = $this->channel->pop(0.1);
                if ($result !== false) {
                    yield $result['result'];
                    unset($coroutines[$result['key']]);
                }
            }

            $coroutineKey = $nextCoroutineKey++;
            $coroutines[$coroutineKey] = true;

            // Создание новой корутины для обработки задачи
            co::create(function () use ($fn, $task, $coroutineKey) {
                $result = call_user_func($fn, $task); // Выполнение функции над задачей
                $this->channel->push(['key' => $coroutineKey, 'result' => $result], 0.1);
            });
        }

        // Завершение работы всех корутин после обработки всех задач
        while (!empty($coroutines)) {
            $result = $this->channel->pop(0.1);
            if ($result !== false) {
                yield $result['result'];
                unset($coroutines[$result['key']]);
            }
        }
    }
}