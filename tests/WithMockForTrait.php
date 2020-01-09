<?php


namespace Tests;


trait WithMockForTrait
{
    public function mockForTrait(string $traitName, array $mockedMethods)
    {
        return $this->getMockForTrait($traitName, [], '', true, true, true, $mockedMethods);
    }
}
