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

use Smatyas\Mfw\Http\Request;

class SecuredPageController extends BaseController
{
    public function page1Action(Request $request)
    {
        return [
            'header' => $this->getHeaderContent($request, 'Secured page 1'),
            'controller' => __METHOD__,
            'session_data' => json_encode($_SESSION),
            'footer' => $this->get('templating')->render('footer.html.tpl'),
        ];
    }

    public function page2Action(Request $request)
    {
        return [
            'header' => $this->getHeaderContent($request, 'Secured page 2'),
            'controller' => __METHOD__,
            'session_data' => json_encode($_SESSION),
            'footer' => $this->get('templating')->render('footer.html.tpl'),
        ];
    }
}
