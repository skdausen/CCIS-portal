<?php
// PreventBackHistory.php
namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class PreventBackHistory implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Nothing before
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // ✅ SET NO-CACHE HEADERS FOR ALL RESPONSES
        $response->setHeader('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
        $response->setHeader('Cache-Control', 'post-check=0, pre-check=0', false);
        $response->setHeader('Pragma', 'no-cache');
    }
}
