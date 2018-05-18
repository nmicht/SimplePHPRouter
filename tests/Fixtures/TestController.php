<?php
namespace SimplePHPRouterTest;

final class TestController
{
    public function page()
    {
        return func_get_args();
    }
}
