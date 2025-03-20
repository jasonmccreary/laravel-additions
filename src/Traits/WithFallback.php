<?php

namespace JMac\Additions\Traits;

trait WithFallback
{
    // Gate::update(User $user, Post $post)
    // $name = 'update'
    // $arguments = [$user, $post]


    public function __call($name, $arguments)
    {
        if (!method_exists($this, 'fallback')) {
            throw new \RuntimeException('You must define a fallback method when using the WithFallback trait');
        }

        // TODO: determine "shifting" arguments to `fallback`
        // use cases:
        //   - "resourceful" methods with user and model
        //   - "resourceful" methods without model
        //   - with additional arguments
        //   - non-"resourceful" methods with additional arguments
        dump($arguments);

        return $this->fallback($name, $user, $model, ...$arguments);
    }
}
