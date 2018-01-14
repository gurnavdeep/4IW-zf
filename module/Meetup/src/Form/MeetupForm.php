<?php
declare(strict_types = 1);

namespace Meetup\Form;

use Zend\Form\Element;
use Zend\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Validator\Date;
use Zend\Validator\GreaterThan;
use Zend\Validator\StringLength;
use Zend\Validator\Callback;

class MeetupForm extends Form implements InputFilterProviderInterface
{
    public function __construct()
    {
        parent::__construct('meetup');
        $this->add([
            'type' => Element\Text::class,
            'name' => 'title',
            'options' => [
                'label' => 'Title',
            ],
            'attributes' => [
                'placeholder' => 'Meetup title',
                'class' => 'form-control'
            ]
        ]);
        $this->add([
            'type' => Element\Submit::class,
            'name' => 'submit',
            'attributes' => [
                'value' => 'Submit',
                'class' => 'btn btn-success'
            ],
        ]);
        $this->add([
            'type' => Element\Textarea::class,
            'name' => 'description',
            'options' => [
                'label' => 'Description',
            ],
            'attributes' => [
                'placeholder' => 'Meetup description',
                'class' => 'form-control',
                'rows' => 4
            ]
        ]);
        $this->add([
            'type' => Element\Date::class,
            'name' => 'date_debut',
            'options' => [
                'label' => 'Start date',
            ],
            'attributes' => [
                'class' => 'form-control',
                'min' => '2018-01-01',
                'max' => '2018-12-31',
                'step' => '1',
            ]
        ]);
        $this->add([
            'type' => Element\Date::class,
            'name' => 'date_fin',
            'options' => [
                'label' => 'End date',
            ],
            'attributes' => [
                'class' => 'form-control',
                'step' => '1',
                'max' => '2018-12-31',
                'min' => '2018-01-01',
            ]
        ]);
    }
    
    public function getInputFilterSpecification()
    {

        return [
            'title' => [
                'required' => true,
                'validators' => [
                    [
                        'name' => StringLength::class,
                        'options' => [
                            'min' => 5,
                            'max' => 20,
                        ],
                    ],
                ],
            ],
            'description' => [
                'required' => true,
                'validators' => [
                    [
                        'name' => StringLength::class,
                        'options' => [
                            'min' => 1,
                            'max' => 500,
                        ],
                    ]
                ]
            ],
            'date_debut' => [
                'required' => true,
                'validators' => [
                    [
                        'name' => GreaterThan::class,
                        'options' => [
                            'min' => date('Y-m-d'),
                            'inclusive' => true,
                        ],
                    ],
                    [
                        'name' => Date::class,
                        'options' => [
                            'format' => 'Y-m-d',
                        ]
                    ]
                ]
            ],
            'date_fin' => [
                'required' => true,
                'validators' => [
                    [
                        'name' => GreaterThan::class,
                        'options' => [
                            'min' => date('Y-m-d'),
                            'inclusive' => true,
                        ],
                    ],
                    [
                        'name' => Date::class,
                        'options' => [
                            'format' => 'Y-m-d',
                        ]
                    ],
                    [
                        'name' => Callback::class,
                        'options' => [
                            'callback' => function ($value, $context = []){
                                $date_debut = new Date($context['date_debut'] ?? null);
                                $date_fin = new Date($value);

                                return $date_debut <= $date_fin;
                            },
                            'messages' => [
                                Callback::INVALID_VALUE => '%value% must be greater than the start date',
                            ],
                        ]
                    ]
                ]
            ]
        ];
    }
}
