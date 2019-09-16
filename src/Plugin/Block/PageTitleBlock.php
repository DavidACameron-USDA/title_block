<?php

namespace Drupal\title_block\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Block\TitleBlockPluginInterface;
use Drupal\Core\Controller\TitleResolverInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Provides a block to display the page title.
 *
 * @Block(
 *   id = "nal_page_title_block",
 *   admin_label = @Translation("Page Title")
 * )
 */
class PageTitleBlock extends BlockBase implements TitleBlockPluginInterface, ContainerFactoryPluginInterface {

  /**
   * The page title: a string (plain title) or a render array (formatted title).
   *
   * @var string|array|null
   */
  protected $title = NULL;

  /**
   * The current request object.
   *
   * @var \Symfony\Component\HttpFoundation\Request
   */
  protected $request;

  /**
   * The current route object.
   *
   * @var \Symfony\Component\Routing\Route|null
   */
  protected $route;

  /**
   * The title_resolver service.
   *
   * @var \Drupal\Core\Controller\TitleResolverInterface
   */
  protected $titleResolver;

  /**
   * Constructs a new PageTitleBlock object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Symfony\Component\HttpFoundation\RequestStack $request_stack
   *   The request_stack service.
   * @param \Drupal\Core\Routing\RouteMatchInterface $current_route_match
   *   The current_route_match service.
   * @param \Drupal\Core\Controller\TitleResolverInterface $title_resolver
   *   The title_resolver service.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, RequestStack $request_stack, RouteMatchInterface $current_route_match, TitleResolverInterface $title_resolver) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->request = $request_stack->getCurrentRequest();
    $this->route = $current_route_match->getRouteObject();
    $this->titleResolver = $title_resolver;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('request_stack'),
      $container->get('current_route_match'),
      $container->get('title_resolver')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function setTitle($title) {
    $this->title = $title;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return ['label_display' => FALSE];
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    return [
      '#type' => 'page_title',
      '#title' => !is_null($this->title) ? $this->title : $this->titleResolver->getTitle($this->request, $this->route),
    ];
  }

}

