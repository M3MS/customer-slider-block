<?php

/**
 *  Customer Slider block
 *
 * @package mytheme
 */

namespace MyTheme\ACF\FieldGroups\Blocks\Sections\CustomerSlider;

use StoutLogic\AcfBuilder\FieldsBuilder;
use MyTheme\ACF\FieldGroups\Helper;
use MyTheme\Helper\Template;

use WP_Query;

class Block
{

    static $name;
    static $block;

    public function __construct()
    {
        self::$name = strtolower(basename(__DIR__));

        if (function_exists('acf_add_local_field_group')) {
            $this->register_block();
            $this->register_fields();
        }
    }

    public function register_block()
    {
        self::$block = [
            'name'                  => self::$name,
            'title'                 => 'Customer Slider',
            'description'           => 'Block to display customer slider',
            'render_callback'       => [$this, 'render'],
            'category'              => 'layout',
            'keywords'              => ['customer', 'featured', 'quote'],
            'align'                 => 'full',
            'mode'                  => 'edit',
            'supports'              => [
                'align' => true,
                'mode' => true
            ],
        ];
        acf_register_block_type(self::$block);
    }

    public function register_fields()
    {
        $block = new FieldsBuilder(self::$block['name'], [
            'title' => self::$block['title'],
            'position' => 'normal',
            'style' => 'default',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'active' => '1',
        ]);
        $block
            ->addRepeater('customers', [
                'layout' => 'block'
            ])

            ->addTextarea('quote', [
                'wrapper' => [
                    'width' => '50'
                ],
            ])
            ->addImage('image',  [
                'wrapper' => [
                    'width' => '50'
                ],
            ])
            ->addText('name', [
                'wrapper' => [
                    'width' => '50'
                ],
            ])
            ->addPostObject('customer', [
                'post_type' => ['company-logo'],
                'wrapper' => [
                    'width' => '50'
                ],
            ])
            ->addText('role', [
                'wrapper' => [
                    'width' => '50'
                ],
            ])
            ->addPageLink('link', [
                'wrapper' => [
                    'width' => '50'
                ],
                'post_type' => ['customers'],
                'allow_null' => 1,
            ])

            ->endRepeater()

            ->setLocation('block', '==', 'acf/' . self::$block['name']);

        add_action('acf/init', function () use ($block) {
            acf_add_local_field_group($block->build());
        });
    }

    public function render($block, $content = '', $is_preview = false, $post_id = 0)
    {
        $attributes = Helper::create_block_attributes($block);
        $data = get_fields();
        $customers = $data['customers'];
        ?>
        <section id="<?php echo esc_attr($attributes['id']); ?>" class="<?php echo esc_attr($attributes['class']); ?> customer-slider is-rounded container bg-midnight my-6 px-md-0">
            <?php if ($customers) : ?>
                <div class="glide">
                    <div class="customer-slider__nav glide__bullets d-flex justify-content-center pt-lg-4 pt-3 pb-2" data-glide-el="controls[nav]">
                        <?php
                        $count = 0;
                        foreach ($customers as $client) :
                            $customer = $client['customer'];
                            $logo = get_field('logo', $customer->ID); ?>
                            <button class="glide__bullet rounded-2 px-3 mx-2 d-flex align-items-center" data-glide-dir="=<?= $count  ?>">
                                <?php
                                    Template::image($logo, 'full', [
                                        'class' => 'logo'
                                    ]);
                                ?>
                            </button>
                        <?php
                        $count++;
                        endforeach; ?>
                    </div>
                    <div class="glide__track" data-glide-el="track">
                        <div class="glide__slides">
                            <?php foreach ($customers as $client) :
                                $quote = $client['quote'];
                                $name = $client['name'];
                                $role = $client['role'];
                                $image = $client['image'];
                                $link = $client['link']; ?>
                                <div class="d-flex align-items-center glide__slide customer-item">
                                    <div class="col-lg-7 d-flex">
                                        <div class="inner">
                                            <?php if (!empty($quote)) : ?>
                                                <figure class="mb-5">
                                                    <blockquote>
                                                        <?php Template::svg('quote_rounded_left_white', 'icons'); ?>
                                                        <p class="h4 mb-5"><?= $quote ?></p>
                                                    </blockquote>
                                                    <figcaption>
                                                        <?php if (!empty($name)) : ?>
                                                            <span class="customer-quote__name mb-0"><?= $name ?></span>
                                                        <?php endif;
                                                        if (!empty($role)) : ?>
                                                            <span class="customer-quote__role"><?= $role ?></span>
                                                        <?php endif; ?>
                                                    </figcaption>
                                                </figure>
                                            <?php endif;
                                            if (!empty($link)) :
                                                $button_args = [
                                                    'title'                     => 'Read customer story',
                                                    'link'                      => $link,
                                                    'target'                    => '',
                                                    'color'                     => 'amber',
                                                    'arrow_direction'           => 'right',
                                                ];
                                                Template::button($button_args);
                                            endif;?>
                                        </div>
                                    </div>
                                    <div class="col-lg-5 p-0 d-none d-lg-block">
                                        <?php if (!empty($image)) : ?>
                                            <?php
                                            Template::image($image, 'full', [
                                                'class' => 'rounded-4'
                                            ]); ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </section>
        <?php
    }
}
