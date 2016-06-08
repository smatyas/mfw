<?php
/**
 * This file is part of the mfw package.
 *
 * (c) Mátyás Somfai <somfai.matyas@gmail.com>
 * Created at 2016.06.08.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Smatyas\MfwApp\Controller;

use Smatyas\Mfw\Controller\AbstractController;
use Smatyas\Mfw\Http\Request;

abstract class BaseController extends AbstractController
{
    public function getHeaderContent(Request $request)
    {
        $user1LinkClass = $user2LinkClass = '';
        switch ($request->getUri()) {
            case '/user1':
                $user1LinkClass = 'active';
                break;
            
            case '/user2':
                $user2LinkClass = 'active';
                break;
        }
        if (isset($_SESSION['user'])) {
            $username = $_SESSION['user']['username'];
            $submenuPath = '/logout';
            $submenuTitle = 'Log out';
        } else {
            $username = 'Sign in';
            $submenuPath = '/login';
            $submenuTitle = 'Login page';
        }
        $headerContent = $this->get('templating')->render(
            'header.html.tpl',
            [
                'title' => 'Main page',
                'user1_link_class' => $user1LinkClass,
                'user2_link_class' => $user2LinkClass,
                'username' => $username,
                'submenu_path' => $submenuPath,
                'submenu_title' => $submenuTitle,
            ]
        );

        return $headerContent;
    }
}
