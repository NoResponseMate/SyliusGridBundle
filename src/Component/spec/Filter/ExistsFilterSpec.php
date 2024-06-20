<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Sylius Sp. z o.o.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace spec\Sylius\Component\Grid\Filter;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sylius\Component\Grid\Data\DataSourceInterface;
use Sylius\Component\Grid\Data\ExpressionBuilderInterface;
use Sylius\Component\Grid\Filter\ExistsFilter;
use Sylius\Component\Grid\Filtering\FilterInterface;

final class ExistsFilterSpec extends ObjectBehavior
{
    function it_implements_filter_interface(): void
    {
        $this->shouldImplement(FilterInterface::class);
    }

    function it_does_nothing_the_data_is_null(DataSourceInterface $dataSource): void
    {
        $dataSource->restrict(Argument::any())->shouldNotBeCalled();

        $this->apply($dataSource, Argument::any(), null, []);
    }

    function it_does_nothing_if_the_data_is_an_empty_string(DataSourceInterface $dataSource): void
    {
        $dataSource->restrict(Argument::any())->shouldNotBeCalled();

        $this->apply($dataSource, Argument::any(), '', []);
    }

    function it_filters_off_all_data_with_provided_field_equal_to_null(
        DataSourceInterface $dataSource,
        ExpressionBuilderInterface $expressionBuilder,
    ): void {
        $dataSource->getExpressionBuilder()->willReturn($expressionBuilder);

        $expressionBuilder->isNotNull('fieldName')->willReturn($expressionBuilder);
        $dataSource->restrict($expressionBuilder)->shouldBeCalled();

        $this->apply($dataSource, Argument::any(), ExistsFilter::TRUE, ['field' => 'fieldName']);
    }

    function it_filters_off_all_data_with_provided_field_not_equal_to_null(
        DataSourceInterface $dataSource,
        ExpressionBuilderInterface $expressionBuilder,
    ): void {
        $dataSource->getExpressionBuilder()->willReturn($expressionBuilder);

        $expressionBuilder->isNull('fieldName')->willReturn($expressionBuilder);
        $dataSource->restrict($expressionBuilder)->shouldBeCalled();

        $this->apply($dataSource, Argument::any(), ExistsFilter::FALSE, ['field' => 'fieldName']);
    }

    function it_filters_off_data_by_filters_name_if_field_is_not_provided(
        DataSourceInterface $dataSource,
        ExpressionBuilderInterface $expressionBuilder,
    ): void {
        $dataSource->getExpressionBuilder()->willReturn($expressionBuilder);

        $expressionBuilder->isNull('filterName')->willReturn($expressionBuilder);
        $dataSource->restrict($expressionBuilder)->shouldBeCalled();

        $this->apply($dataSource, 'filterName', ExistsFilter::FALSE, []);
    }
}
