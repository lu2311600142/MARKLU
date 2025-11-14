<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * RoleAuth Filter - Checks if user has the correct role to access routes
 * This filter checks role-based permissions after AuthFilter confirms user is logged in
 */
class RoleAuth implements FilterInterface
{
    /**
     * before() - Checks user role before allowing access to routes
     * 
     * @param RequestInterface $request
     * @param array|null $arguments
     * @return mixed
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        // Double-check if user is logged in (should be handled by AuthFilter first)
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Please login first.');
        }

        // Get users role from session
        $userRole = $session->get('role');
        
        // Get the current URI to determine required permissions
        $uri = $request->getUri();
        $path = $uri->getPath();

        // Check if accessing admin routes
        if (strpos($path, 'admin') !== false) {
            // Only admins can access admin routes
            if ($userRole !== 'admin') {
                return $this->redirectBasedOnRole($userRole, 'admin');
            }
        }
        
        // Check if accessing teacher routes
        if (strpos($path, 'teacher') !== false) {
            // Teachers and admins can access teacher routes
            if ($userRole !== 'teacher' && $userRole !== 'admin') {
                return $this->redirectBasedOnRole($userRole, 'teacher');
            }
        }

        // User has permission, allow access
        return;
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

    /**
     * Helper method to redirect users based on their role
     * 
     * @param string $userRole - The user's current role
     * @param string $attemptedAccess - What they tried to access (admin/teacher)
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    private function redirectBasedOnRole($userRole, $attemptedAccess)
    {
        $message = 'Access Denied: Insufficient Permissions.';
        
        switch ($userRole) {
            case 'admin':
                // Admin shouldn't reach here, but just in case
                return redirect()->to('/admin/dashboard')->with('error', $message);
                
            case 'teacher':
                // Teacher tried to access admin area
                return redirect()->to('/teacher/dashboard')->with('error', $message);
                
            case 'student':
            default:
                // Student tried to access admin or teacher area
                return redirect()->to('/announcements')->with('error', $message);
        }
    }
}