<?php

namespace Test\Unit\Commands\Vk\Base;

use Arslav\Newbot\Commands\Vk\Base\VkCommand;
use Codeception\Stub\Expected;
use Codeception\Test\Unit;
use Exception;

class VkCommandTest extends Unit
{
    private mixed $data;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $dataArray = [
            'object' => [
                'peer_id' => 1,
                'text' => 'test',
                'payload' => [],
                'from_id' => 1,
            ],
            'type' => 'message_new'
        ];
        $this->data = json_decode(json_encode($dataArray), false);
    }

    /**
     * @return void
     *
     * @throws Exception
     */
    public function testRun()
    {
        $command = $this->construct(
            VkCommand::class,
            [['test']],
            ['run' => Expected::once()]
        );
        $command->init($this->data);
        $command->run();
    }

    /**
     * @return void
     *
     * @throws Exception
     */
    public function testInit()
    {
        $command = $this->construct(
            VkCommand::class,
            [['test']],
            ['run' => Expected::never()]
        );
        $command->init($this->data);
        $this->assertIsObject($command->data);
        $this->assertIsString($command->message);
        $this->assertIsInt($command->peer_id);
        $this->assertIsInt($command->from_id);
        $this->assertIsInt($command->chat_id);
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
        $this->data->object->peer_id = $peer_id;
        $command = $this->construct(
            VkCommand::class,
            [['test']],
            ['run' => Expected::never()]
        );
        $command->init($this->data);
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
