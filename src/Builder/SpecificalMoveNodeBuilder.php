<?php


namespace App\Builder;


use App\Exception\UnknownMapping;
use App\Helper\MoveSetHelper;
use App\Helper\NumberHelper;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\Ternary;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Name;
use PhpParser\Node\Scalar\LNumber;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Expression;

class SpecificalMoveNodeBuilder
{
    private CommonMoveSetBuilder $commonMoveSetBuilder;

    public function __construct(CommonMoveSetBuilder $commonMoveSetBuilder)
    {
        $this->commonMoveSetBuilder = $commonMoveSetBuilder;
    }

    public function getSpecificMoveNodes( string $moveType, array $propertiesData)
    {
        $nodes = [];
        $i = 1;
        $complexes = $this->getComplexesMoves($propertiesData);
        for ($j = 0; $j < $complexes ; $j++) {
            array_merge($nodes,$this->commonMoveSetBuilder->getCommonMoveSet($moveType));
            foreach ($propertiesData as $property) {
                $complexeMoveType = key($property);
                $propertyName = $property[$complexeMoveType];
                if ($complexeMoveType === 'integer') {
                    $nodes[] = new Expression(new MethodCall(
                            new Variable('propertyAccessor'),
                            new Name('setValue'),
                            [
                                new Arg(new Variable('moveEntity')),
                                new Arg(new String_($propertyName)),
                                new Arg(
                                    new StaticCall(
                                        new Name\FullyQualified(
                                            NumberHelper::class
                                        ),
                                        'formatNumber',
                                        [
                                            new Arg(
                                                new ArrayDimFetch(
                                                    new ArrayDimFetch(new Variable('move'), new String_('value')),
                                                    new LNumber($i)
                                                )
                                            )
                                        ]
                                    )
                                )
                            ]
                        )
                    );
                } elseif ($complexeMoveType === 'string') {
                    $nodes[] = new Expression(new MethodCall(
                            new Variable('propertyAccessor'),
                            new Name('setValue'),
                            [
                                new Arg(new Variable('moveEntity')),
                                new Arg(new String_($propertyName)),
                                new Arg(
                                    new ArrayDimFetch(
                                        new ArrayDimFetch(
                                            new Variable('move'), new String_('value')
                                        ),
                                        new LNumber($i)
                                    )
                                )
                            ]
                        )
                    );
                } elseif ($complexeMoveType === 'complex') {
                    $games = $propertyName['games'];
                    $fields = $propertyName['fields'];

                    foreach ($games as $game) {
                        $nodes[] = new Expression(new MethodCall(
                                new Variable('propertyAccessor'),
                                new Name('setValue'),
                                [
                                    new Arg(new Variable('moveEntity')),
                                    new Arg(new String_($game)),
                                    new Arg(new ConstFetch(new Name('true')))
                                ]
                            )
                        );
                    }
                } else {
                    throw new UnknownMapping('Unknown property type ' . $complexeMoveType);
                }
                $i++;
            }
            $nodes[] = new Expression(new MethodCall(new Variable('em'), 'persist', [new Arg(new Variable('moveEntity'))]));
        }
        return $nodes;
    }

    private function getComplexesMoves(array $propertiesDatas)
    {
        $complexeMoves = 0;
        foreach ($propertiesDatas as $iValue) {
            $move = $iValue;
            reset($move);
            if (key($move) === 'complex') {
                $complexeMoves++;
            }
        }
        return $complexeMoves;
    }
}