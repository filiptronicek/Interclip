<?php

it('can return formatted string given a length of bytes')
    ->expect(formatBytes(70656))->toBe('69 KB');

it('can fetch all local git branches and return an array')
    ->expect(getBranches())->not()->toBeEmpty();

it('can return the current OS if a match was found')
    ->expect(getOS())->not()->toBeEmpty();
