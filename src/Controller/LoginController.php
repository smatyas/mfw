<?php
/**
 * This file is part of the mfw package.
 *
 * (c) Mátyás Somfai <somfai.matyas@gmail.com>
 * Created at 2016.06.05.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Smatyas\MfwApp\Controller;

use Gregwar\Captcha\CaptchaBuilder;
use Smatyas\Mfw\Controller\AbstractController;
use Smatyas\Mfw\Http\RedirectResponse;
use Smatyas\Mfw\Http\Request;
use Smatyas\Mfw\Http\Response;
use Smatyas\Mfw\Orm\Manager;
use Smatyas\MfwApp\Entity\User;
use Smatyas\MfwApp\Entity\UserRepository;

class LoginController extends AbstractController
{
    public function indexAction()
    {
        $errors = '';
        if (isset($_SESSION['flashes']['error'])) {
            foreach ($_SESSION['flashes']['error'] as $message) {
                $errors .= $this->get('templating')->render('error.html.tpl', ['message' => $message]);
            }
            unset($_SESSION['flashes']['error']);
        }
        return [
            'errors' => $errors,
            'header' => $this->get('templating')->render('header.html.tpl', ['title' => 'Login']),
            'footer' => $this->get('templating')->render('footer.html.tpl'),
        ];
    }

    public function checkAction(Request $request)
    {
        if ($request->getPostParameter('captcha') !== $this->getCaptchaBuilder()->getPhrase()) {
            // invalid captcha value provided
            $_SESSION['flashes']['error'][] = 'Invalid captcha value.';
            // reset the captcha
            unset($_SESSION['captcha_phrase']);

            // redirect to the login page
            return new RedirectResponse('/login');
        }

        // Checking user credentials.
        /** @var Manager $om */
        $om = $this->get('orm.manager');
        /** @var UserRepository $repo */
        $repo = $om->getRepository(User::class);
        $user = $repo->findByUsername($request->getPostParameter('username'));
        if (null !== $user && $user->matchPassword($request->getPostParameter('password'))) {
            // User successfully authenticated.
            $_SESSION['user'] = [
                'id' => $user->getId(),
                'username' => $user->getUsername(),
                'roles' => $user->getRoles(),
            ];

            // redirect to the main page
            return new RedirectResponse('/');
        } else {
            $_SESSION['flashes']['error'][] = 'Invalid username or password.';

            // redirect to the login page
            return new RedirectResponse('/login');
        }
    }

    public function captchaAction()
    {
        $captchaBuilder = $this->getCaptchaBuilder();
        $captchaBuilder->build();

        $response = new Response($captchaBuilder->get(), 200, ['Content-type: image/jpeg']);
        return $response;
    }

    /**
     * @return CaptchaBuilder
     */
    protected function getCaptchaBuilder()
    {
        $phrase = isset($_SESSION['captcha_phrase']) ? $_SESSION['captcha_phrase'] : null;
        $captchaBuilder = new CaptchaBuilder($phrase);
        if (null === $phrase) {
            $_SESSION['captcha_phrase'] = $captchaBuilder->getPhrase();
        }

        return $captchaBuilder;
    }

    public function logoutAction()
    {
        session_destroy();
        
        return new RedirectResponse('/');
    }
}
