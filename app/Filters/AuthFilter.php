<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * AuthFilter - Checks if user is logged in before allowing access
 * This filter only checks authentication status, not role permissions.
 * For role-based access control, use RoleAuth filter.
 */
class AuthFilter implements FilterInterface
{
    /**
     * before() - Checks if user is logged in
     * 
     * @param RequestInterface $request
     * @param array|null $arguments
     * @return mixed
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        // Check if user is logged in
        if (!$session->get('isLoggedIn')) {
            // User is not logged in, redirect to login page
            return redirect()->to('/login')->with('error', 'Please login first.');
        }

        // User is logged in, allow access to continue
        // The RoleAuth filter (if applied) will handle role-based restrictions
    }

    /**
     * after() - Perform any actions after the controller
     * 
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @param array|null $arguments
     * @return mixed
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No action needed after controller execution
    }
}