<?php

use App\Models\Attribute;

it('has welcome page')->get('/')->assertStatus(200);

beforeEach(function () {
    Attribute::factory(10)->create();
});


it('ensure that 10 is greater than 5', function () {
    expect(10)->toBeGreaterThan(5);
});

it('ensures that 10 users are created', function () {

    expect(Attribute::count())->toEqual(10);
    expect(Attribute::all())->toHaveCount(10);
});

it('test names', function ($name) {
    expect($name)->not->toBeIn(['John', 'Julie']);
})->with('names');
