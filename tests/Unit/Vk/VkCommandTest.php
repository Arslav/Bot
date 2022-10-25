<?php

namespace Tests\Unit\Vk;

use stdClass;
use Arslav\Bot\Vk\Command;
use Codeception\Stub\Expected;
use Codeception\Test\Unit;
use Exception;
use Tests\Support\UnitTester;

class VkCommandTest extends Unit
{
    public UnitTester $tester;

    /**
     * @return void
     *
     * @throws Exception
     */
    public function testRun(): void
    {
        $this->tester->sendVkMessage('test');
        $command = $this->construct(
            Command::class,
            [['test']],
            ['execute' => Expected::once()]
        );
        $command->run($this->tester->getVkMessageData());
    }

    /**
     * @param int  $peer_id
     * @param bool $expected
     *
     * @return void
     *
     * @throws Exception
     * @dataProvider peerIdProvider
     */
    public function testIsFromChat(int $peer_id, bool $expected): void
    {
        $this->tester->sendVkMessage('test');
        /** @var stdClass $data */
        $data = $this->tester->getVkMessageData();
        $data->object->peer_id = $peer_id;
        $command = $this->construct(
            Command::class,
            [['test']],
            ['execute' => Expected::once()]
        );
        $command->run($data);
        $this->assertSame($expected, $command->isFromChat());
    }

    /**
     * @return array[]
     */
    public function peerIdProvider(): array
    {
        return [
            [1, false],
            [2000000012, true]
        ];
    }
}
