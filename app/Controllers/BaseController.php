<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Models\NotificationModel;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var list<string>
     */
    protected $helpers = ['url', 'form'];
    
    /**
     * @var NotificationModel
     */
    protected $notificationModel;
    
    /**
     * @var int
     */
    protected $unreadCount = 0;

    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
    // protected $session;

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc., here.
        $this->session = \Config\Services::session();
        
        // Initialize notification model
        $this->notificationModel = new NotificationModel();
        
        // Set unread notification count for the current user
        if ($this->session->get('isLoggedIn')) {
            $this->unreadCount = $this->notificationModel->getUnreadCount($this->session->get('id'));
            
            // Make unread count available to all views
            $this->setUnreadCountForView();
        }
    }
    
    /**
     * Set unread notification count for the view
     */
    protected function setUnreadCountForView()
    {
        helper('url');
        
        // Add notification data to all views
        $data = [
            'unreadCount' => $this->unreadCount,
            'notifications' => $this->notificationModel->getNotificationsForUser($this->session->get('id'), 5)
        ];
        
        // Share with all views
        $renderer = \Config\Services::renderer();
        $renderer->setData($data);
    }
}
