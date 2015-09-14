<?php
class TextWidget extends BaseWidget{
    protected $keys = ['text'];
}

$widgets_registration->register('text', 'TextWidget');
