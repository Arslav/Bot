<?php

namespace Tests\Unit\Commands\Vk\Base;

use Arslav\Newbot\Commands\Vk\Base\VkCommand;
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
    public function testRun()
    {
        $this->tester->sendMessage('test');
        $command = $this->construct(
            VkCommand::class,
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
    public function testInit()
    {
        $this->tester->sendMessage('test');
        $command = $this->construct(
            VkCommand::class,
            [['test']],
            ['run' => Expected::never()]
        );
        $command->init($this->tester->getVkMessageData());
        $this->assertIsObject($command->data);
        $this->assertIsString($command->message);
        $this->assertIsInt($command->peer_id);
        $this->assertIsInt($command->from_id);
        $this->assertNull($command->chat_id);
    }

    /**
     * @return void
     *
     * @throws Exception
     *
     * @dataProvider peerIdProvider
     */
    public function testIsFromChat(int $peer_id, bool $expected)
    {
        $this->tester->sendMessage('test');
        $data = $this->tester->getVkMessageData();
        $data->object->peer_id = $peer_id;
        $command = $this->construct(
            VkCommand::class,
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
