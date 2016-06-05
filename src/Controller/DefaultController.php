<?php
/**
 * This file is part of the mfw package.
 *
 * (c) Mátyás Somfai <somfai.matyas@gmail.com>
 * Created at 2016.06.03.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Smatyas\MfwApp\Controller;


use Smatyas\Mfw\Controller\AbstractController;

class DefaultController extends AbstractController
{
    public function indexAction()
    {
        phpinfo();
    }
}
