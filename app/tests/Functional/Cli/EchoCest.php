<?php


namespace Tests\Functional\Cli;

use Tests\Support\FunctionalTester;

class EchoCest
{
    /**
     * @param FunctionalTester $I
     *
     * @return void
     */
    public function tryToTest(FunctionalTester $I)
    {
        $I->wantToTest('Echo command');
        $I->runShellCommand('bin/console echo test');
        $I->seeResultCodeIs(0);
        $I->canSeeInShellOutput('test');
    }
}
