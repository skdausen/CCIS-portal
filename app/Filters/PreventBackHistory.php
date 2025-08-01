<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class PreventBackHistory implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // No changes before the request
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Set headers to prevent caching
        $response->setHeader('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
        $response->setHeader('Cache-Control', 'post-check=0, pre-check=0');
        $response->setHeader('Pragma', 'no-cache');
        $response->setHeader('Expires', 'Sat, 01 Jan 2000 00:00:00 GMT');
    }
}
