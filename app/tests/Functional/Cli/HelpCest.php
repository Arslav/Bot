<?php


namespace Tests\Functional\Cli;

use Tests\Support\FunctionalTester;

class HelpCest
{
    /**
     * @param FunctionalTester $I
     *
     * @return void
     */
    public function testRunWithoutParams(FunctionalTester $I)
    {
        $I->wantToTest('Help command without param');
        $I->runShellCommand('bin/console');
        $I->seeResultCodeIs(0);
        $I->seeInShellOutput('Доступные команды:');
    }

    /**
     * @param FunctionalTester $I
     *
     * @return void
     */
    public function testRun(FunctionalTester $I)
    {
        $I->wantToTest('Help command');
        $I->runShellCommand('bin/console help');
        $I->seeResultCodeIs(0);
        $I->seeInShellOutput('Доступные команды:');
    }

    /**
     * @param FunctionalTester $I
     *
     * @return void
     */
    public function testRunUnknown(FunctionalTester $I)
    {
        $I->wantToTest('Help command when command doesn\'t exist');
        $I->runShellCommand('bin/console unknown');
        $I->seeResultCodeIs(0);
        $I->seeInShellOutput('Доступные команды:');
        $I->seeInShellOutput('Ошибка');
    }
}
