<?php

namespace App\View\Components\Admin;

use Illuminate\Support\Str;
use Illuminate\View\Component;

class ConfirmModal extends Component
{
    /**
     * Unique id for front-end
     *
     * @param string $id
     */
    public string $id;

    /**
     * Form Method
     *
     * @param string $method
     */
    public string $method;

    /**
     * Modal Title
     *
     * @param string $title
     */
    public string $title;

    /**
     * Modal Message
     *
     * @param string $id
     */
    public string $message;

    /**
     * Modal Button Text
     *
     * @param string $id
     */
    public string $buttonText;

    /**
     * Modal Button Type
     *
     * @param string $id
     */
    public string $buttonType;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        string $id,
        string $method = 'post',
        string $title,
        string $message,
        string $buttonText,
        string $buttonType = 'primary'
    ) {
        $this->id = $id;
        $this->method = $method;
        $this->title = $title;
        $this->message = $message;
        $this->buttonText = $buttonText;
        $this->buttonType = $buttonType;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.admin.confirm-modal');
    }
}
