<?php

/*
 * Created by Samuel Moncarey
 * 20/06/2017
 */

namespace MoncaretWS\ContentWidgetsBundle\HttpFoundation;


use Symfony\Component\HttpFoundation\JsonResponse;


/**
 * Class WidgetPluginResponse
 * @package MoncaretWS\ContentWidgetsBundle\HttpFoundation
 */
class WidgetPluginResponse extends JsonResponse {

    /**
     * @var string
     */
    private $action = '';

    /**
     * @var string
     */
    private $html = '';

    /**
     * @var array
     */
    private $messages = [];


    /**
     * WidgetPluginResponse constructor.
     */
    public function __construct() {
        parent::__construct(['action' => $this->action, 'html' => $this->html, 'messages' => $this->messages], 200, [], false);
    }

    /**
     * @param string $action
     *
     * @return WidgetPluginResponse
     */
    public function setAction($action) {
        $this->action = $action;
        $this->resetData();
        return $this;
    }

    /**
     * @param string $html
     *
     * @return WidgetPluginResponse
     */
    public function setHtml($html) {
        $this->html = $html;
        $this->resetData();
        return $this;
    }

    /**
     * @param string $type
     * @param string $message
     *
     * @return WidgetPluginResponse
     */
    public function addMessage($type, $message) {
        if (!isset($this->messages[$type])) $this->messages[$type] = [];
        $this->messages[$type][] = $message;
        $this->resetData();
        return $this;
    }

    private function resetData() {
        $this->setData([
            'action' => $this->action,
            'html' => $this->html,
            'messages' => $this->messages
        ]);
    }
}