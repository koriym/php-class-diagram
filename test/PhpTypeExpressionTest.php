<?php declare(strict_types=1);

use PhpParser\ParserFactory;
use PHPUnit\Framework\TestCase;

use Smeghead\PhpClassDiagram\Php\PhpTypeExpression;

final class PhpTypeExpressionTest extends TestCase {
    private $fixtureDir;
    public function setUp(): void {
        $this->fixtureDir = sprintf('%s/fixtures', __DIR__);
    }

    public function testNullableString(): void {
        $parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
        $filename = sprintf('%s/php8/product/Product.php', $this->fixtureDir);
        try {
            $ast = $parser->parse(file_get_contents($filename));
        } catch (Error $error) {
            throw new \Exception("Parse error: {$error->getMessage()} file: {$filename}\n");
        }

        $expression = new PhpTypeExpression($ast[0]->stmts[1]->stmts[0], PhpTypeExpression::TYPE, ['hoge', 'fuga', 'product']);
        $types = $expression->getTypes();

        $this->assertSame([], $types[0]->getNamespace(), 'namespace');
        $this->assertSame('string', $types[0]->getName(), 'name');
        $this->assertSame(true, $types[0]->getNullable(), 'nullable');
    }
    public function testIntOrString(): void {
        $parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
        $filename = sprintf('%s/php8/product/Product.php', $this->fixtureDir);
        try {
            $ast = $parser->parse(file_get_contents($filename));
        } catch (Error $error) {
            throw new \Exception("Parse error: {$error->getMessage()} file: {$filename}\n");
        }
        $expression = new PhpTypeExpression($ast[0]->stmts[1]->stmts[1], PhpTypeExpression::TYPE, ['hoge', 'fuga', 'product']);
        $types = $expression->getTypes();

        $this->assertSame([], $types[0]->getNamespace(), 'namespace');
        $this->assertSame('int', $types[0]->getName(), 'name');
        $this->assertSame(false, $types[0]->getNullable(), 'nullable');
        $this->assertSame([], $types[1]->getNamespace(), 'namespace');
        $this->assertSame('string', $types[1]->getName(), 'name');
        $this->assertSame(false, $types[1]->getNullable(), 'nullable');
    }
    public function testPrice(): void {
        $parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
        $filename = sprintf('%s/php8/product/Product.php', $this->fixtureDir);
        try {
            $ast = $parser->parse(file_get_contents($filename));
        } catch (Error $error) {
            throw new \Exception("Parse error: {$error->getMessage()} file: {$filename}\n");
        }
        $expression = new PhpTypeExpression($ast[0]->stmts[1]->stmts[2], PhpTypeExpression::TYPE, ['hoge', 'fuga', 'product']);
        $types = $expression->getTypes();

        $this->assertSame(['hoge', 'fuga', 'product'], $types[0]->getNamespace(), 'namespace');
        $this->assertSame('Name', $types[0]->getName(), 'name');
        $this->assertSame(false, $types[0]->getNullable(), 'nullable');
    }
    public function testException(): void {
        $parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
        $filename = sprintf('%s/php8/product/Product.php', $this->fixtureDir);
        try {
            $ast = $parser->parse(file_get_contents($filename));
        } catch (Error $error) {
            throw new \Exception("Parse error: {$error->getMessage()} file: {$filename}\n");
        }
        $expression = new PhpTypeExpression($ast[0]->stmts[1]->stmts[4], PhpTypeExpression::TYPE, ['hoge', 'fuga', 'product']);
        $types = $expression->getTypes();

        $this->assertSame([], $types[0]->getNamespace(), 'namespace');
        $this->assertSame('Exception', $types[0]->getName(), 'name');
        $this->assertSame(false, $types[0]->getNullable(), 'nullable');
    }
    public function testRelated(): void {
        $parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
        $filename = sprintf('%s/php8/product/Product.php', $this->fixtureDir);
        try {
            $ast = $parser->parse(file_get_contents($filename));
        } catch (Error $error) {
            throw new \Exception("Parse error: {$error->getMessage()} file: {$filename}\n");
        }
        $expression = new PhpTypeExpression($ast[0]->stmts[1]->stmts[5], PhpTypeExpression::TYPE, ['hoge', 'fuga', 'product']);
        $types = $expression->getTypes();

        $this->assertSame(['hoge', 'fuga', 'product', 'bar'], $types[0]->getNamespace(), 'namespace');
        $this->assertSame('Boo', $types[0]->getName(), 'name');
        $this->assertSame(false, $types[0]->getNullable(), 'nullable');
    }
}
