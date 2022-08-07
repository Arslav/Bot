<?php

use Carbon\Carbon;

return [
    ['command' => 'test1', 'from_id' => 1, 'chat_id' => 1, 'created_at' => Carbon::parse('5.08.2022')],
    ['command' => 'test1', 'from_id' => 1, 'chat_id' => 1],
    ['command' => 'test1', 'from_id' => 1, 'chat_id' => 1],
    ['command' => 'test1', 'from_id' => 1, 'chat_id' => 1],
    ['command' => 'test1', 'from_id' => 1, 'chat_id' => 1],
    ['command' => 'test1', 'from_id' => 1, 'chat_id' => 1],

    ['command' => 'test1', 'from_id' => 1, 'chat_id' => 2],

    ['command' => 'test1', 'from_id' => 1, 'chat_id' => null],
    ['command' => 'test1', 'from_id' => 1, 'chat_id' => null],


    ['command' => 'test2', 'from_id' => 2, 'chat_id' => 2],

    ['command' => 'test2', 'from_id' => 2, 'chat_id' => null, 'created_at' => Carbon::parse('5.08.2022')],
    ['command' => 'test2', 'from_id' => 2, 'chat_id' => null],
];
