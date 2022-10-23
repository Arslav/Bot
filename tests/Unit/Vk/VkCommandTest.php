<?php

namespace Tests\Unit\Vk;

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
            ['run' => Expected::once()]
        );
        $command->init($this->tester->getVkMessageData());
        $command->run();
    }

    /**
     * @return void
     *
     * @throws Exception
     */
    public function testInit(): void
    {
        $this->tester->sendVkMessage('test');
        $command = $this->construct(
            Command::class,
            [['test']],
            ['run' => Expected::never()]
        );
        $command->init($this->tester->getVkMessageData());
        $this->assertIsObject($command->data);
        $this->assertIsString($command->message);
        $this->assertIsInt($command->peerId);
        $this->assertIsInt($command->fromId);
        $this->assertNull($command->chatId);
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
        $data = $this->tester->getVkMessageData();
        $data->object->peer_id = $peer_id;
        $command = $this->construct(
            Command::class,
            [['test']],
            ['run' => Expected::never()]
        );
        $command->init($data);
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
