<?php

namespace markhuot\craftpest\behaviors;

use PHPUnit\Framework\Assert;
use yii\base\Behavior;

use function markhuot\craftpest\helpers\test\test;

/**
 * # Elements
 * 
 * Elements, like entries, and be tested in Craft via the following assertions.
 * 
 * @property \craft\base\Element $owner
 */
class TestableElementBehavior extends Behavior
{
    /**
     * Asserts that the element is valid (contains no errors from validation).
     * 
     * > **Note**
     * > Since validation errors throw Exceptions in Pest, by default, you must
     * > silence those exceptions to continue the test.
     * 
     * ```php
     * Entry::factory()
     *   ->create()
     *   ->assertValid()
     * ```
     */
    function assertValid(array $keys = [])
    {
        Assert::assertCount(0, $this->owner->errors);

        return $this->owner;
    }

    /**
     * Asserts that the element is invalid (contains errors from validation).
     * 
     * ```php
     * Entry::factory()
     *   ->muteValidationErrors()
     *   ->create(['title' => null])
     *   ->assertInvalid();
     * ```
     */
    function assertInvalid(array $keys = [])
    {
        if (count($keys)) {
            $errors = collect($keys)
                ->mapWithKeys(fn ($key) => [$key => $this->owner->getErrors($key)])
                ->filter(fn ($errors) => count($errors) === 0);
            if ($errors->count()) {
                Assert::fail('The following keys were expected to be invalid but were not: '.implode(', ', $errors->keys()->all()));
            }
            else {
                Assert::assertTrue(true);
            }
        }
        else {
            Assert::assertGreaterThanOrEqual(1, count($this->owner->errors));
        }


        return $this->owner;
    }

    /**
     * Check that the element has its `dateDeleted` flag set
     * 
     * ```php
     * $entry = Entry::factory()->create();
     * \Craft::$app->elements->deleteElement($entry);
     * $entry->assertTrashed();
     * ```
     */
    function assertTrashed()
    {
        test()->assertTrashed($this->owner);

        return $this->owner;
    }

    /**
     * Check that the element does not have its `dateDeleted` flag set
     * 
     * ```php
     * Entry::factory()->create()->assertNotTrashed();
     * ```
     */
    function assertNotTrashed()
    {
        test()->assertNotTrashed($this->owner);

        return $this->owner;
    }
}
