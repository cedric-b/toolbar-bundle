<?php

/*
 * Copyright (c) 2011-2017 Lp digital system
 *
 * This file is part of toolbar-bundle.
 *
 * toolbar-bundle is free bundle: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * toolbar-bundle is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with toolbar-bundle. If not, see <http://www.gnu.org/licenses/>.
 */

namespace BackBee\Renderer\Helper;

use Symfony\Component\Security\Core\Exception\AuthenticationCredentialsNotFoundException;

use BackBee\ClassContent\AbstractClassContent;
use BackBee\ClassContent\ContentSet;
use BackBee\Renderer\AbstractRenderer;

/**
 * Helper providing HTML attributes to online-edited content
 *
 * @category    BackBee
 * @package     BackBee\Renderer
 * @subpackage  Helper
 * @copyright   Lp digital system
 * @author      e.chau <eric.chau@lp-digital.fr>
 */
class bbcontent extends AbstractHelper
{
    /**
     * array that contains
     * @var array
     */
    private $attributes;

    /**
     * the classcontent we are processing to get its attributes as string
     * @var AbstractClassContent
     */
    private $content;

    /**
     * @var array
     */
    private $options;

    /**
     * bbcontent helper constructor
     * @param ARenderer $renderer
     */
    public function __construct(AbstractRenderer $renderer)
    {
        parent::__construct($renderer);

        $this->reset();
    }

    /**
     * Return HTML formatted attribute for provided content.
     *
     * @param  AbstractClassContent $content the content we want to generate its HTML attribute;
     *                                       if content is null, we get the current object setted on current renderer
     * @return string
     */
    public function __invoke(AbstractClassContent $content = null, array $options = [])
    {
        $result = '';
        $this->reset();

        $this->content = $content?: $this->getRenderer()->getObject();
        $this->options = $options;

        if ($this->isGranted()) {
            $this->attributes['class'][] = 'bb-content';
            $result = $this->generateAttributesString();
        } else {
            $this->computeClassAttribute();
            $result = $this->getAttributesString();
        }

        return $result;
    }

    /**
     * @return boolean
     */
    private function isGranted()
    {
        $securityContext = $this->getRenderer()->getApplication()->getSecurityContext();

        try {
            $result = (
                null !== $this->getRenderer()->getApplication()->getBBUserToken()
                && $securityContext->isGranted('VIEW', $this->content)
            );
        } catch (AuthenticationCredentialsNotFoundException $e) {
            $result = false;
        }

        return $result;
    }

    /**
     * Resets options.
     */
    private function reset()
    {
        $this->attributes = [
            'class'              => [],
            'data-bb-identifier' => null,
            'data-bb-maxentry' => null
        ];

        $this->content = null;
        $this->options = [];
    }

    /**
     * @return string
     */
    private function generateAttributesString()
    {
        $this->computeClassAttribute();
        $this->computeDragAndDropAttributes();
        $this->computeIdentifierAttribute();
        $this->computeMaxEntryAttribute();
        $this->computeRendermodeAttribute();
        $this->computeAcceptAttribute();
        $this->computeRteAttribute();

        return $this->getAttributesString();
    }

    public function computeRteAttribute()
    {
        $renderer = $this->getRenderer();
        $contentParent = $renderer->getClassContainer();
        if (null !== $contentParent) {
            if ($contentParent instanceof AbstractClassContent && !($contentParent instanceof ContentSet)) {
                $elementName = $renderer->getCurrentElement();
                if (null !== $elementName) {
                    $contentData = $contentParent->{$elementName};
                    /* if it's the same content */
                    if (null !== $contentData && $contentData->getUid() === $this->content->getUid()) {
                        if ($this->content->isElementContent()) {
                            $defaultOptions = $contentParent->getDefaultOptions();

                            if (isset($defaultOptions[$elementName]['parameters']['rte'])) {
                                $this->attributes['data-rteconfig'] = $defaultOptions[$elementName]['parameters']['rte'];
                            }
                        }
                    }
                }
            }
        }
    }

    public function computeAcceptAttribute()
    {
        if ($this->content instanceof ContentSet && 0 < count($this->content->getAccept())) {
            $this->attributes['data-accept'] = implode(',', $this->content->getAccept());
        }
    }

    public function computeRendermodeAttribute()
    {
        $renderer = $this->getRenderer();
        $this->attributes['data-rendermode'] = ($renderer->getMode() !== null) ? $renderer->getMode() : '';
    }

    /**
     * Computes classcontent drag and drop attributes and set it to attributes property array
     */
    private function computeClassAttribute()
    {
        $classes = isset($this->options['class']) ? $this->options['class'] : null;
        $paramClasses = $this->getRenderer()->getParam('class');

        if (null !== $classes || null !== $paramClasses) {
            $this->attributes['class'] = array_merge(
                $this->attributes['class'],
                $classes !== null ? is_array($classes) ? $classes : explode(' ', $classes) : [],
                $paramClasses !== null ? is_array($paramClasses) ? $paramClasses : explode(' ', $paramClasses) : []
            );
        }
    }

    /**
     * Computes classcontent drag and drop attributes and set it to attributes property array
     */
    private function computeDragAndDropAttributes()
    {
        $valid = false;
        if ($this->content instanceof ContentSet) {
            $valid = true === (isset($this->options['dropzone']) ? $this->options['dropzone'] : true);
            $this->attributes['class'][] = 'bb-droppable';
        }

        $is_element = strpos(get_class($this->content), AbstractClassContent::CLASSCONTENT_BASE_NAMESPACE . 'Element\\');
        $is_contentset = get_class($this->content) === AbstractClassContent::CLASSCONTENT_BASE_NAMESPACE . 'ContentSet';
        if (false === $is_element && false === $is_contentset) {
            $valid = true === (isset($this->options['draggable']) ? $this->options['draggable'] : true);
        }

        if ($valid) {
            $this->attributes['class'][] = 'bb-dnd';
        }
    }

    /**
     * Computes classcontent identifier attribute and set it to attributes property array
     */
    private function computeIdentifierAttribute()
    {
        $data = $this->content->jsonSerialize();
        $this->attributes['data-bb-identifier'] = str_replace('\\', '/', $data['type']) . '(' . $data['uid'] . ')';
    }

    /**
     * Computes classcontent maxentry attribute if classcontent is a ContentSet and sets it to attributes property array
     */
    private function computeMaxEntryAttribute()
    {
        if ($this->content instanceof ContentSet) {
            $this->attributes['data-bb-maxentry'] = $this->content->getMaxEntry();
        }
    }

    /**
     * @return string
     */
    private function getAttributesString()
    {
        $result = '';

        foreach ($this->attributes as $key => $value) {
            if (null !== $value) {
                $result .= " $key=\"" . (is_bool($value) ? ($value ? 'true' : 'false') : implode(' ', (array) $value)) . '"';
            }
        }

        return $result;
    }
}
