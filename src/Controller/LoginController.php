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
            'title' => 'Login',
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
        var_dump($_POST);
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
}
