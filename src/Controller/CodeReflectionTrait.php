<?php

declare(strict_types=1);

namespace App\Controller;

use App\Documentation\PHPClass;
use Roave\BetterReflection\BetterReflection;
use Roave\BetterReflection\Reflection\ReflectionClass;
use Roave\BetterReflection\Reflector\ClassReflector;
use Roave\BetterReflection\SourceLocator\Type\AggregateSourceLocator;
use Roave\BetterReflection\SourceLocator\Type\DirectoriesSourceLocator;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

trait CodeReflectionTrait
{
    /**
     * @return PHPClass[]
     */
    protected function codeClassesReflection(string $codePath, string ...$dependenciesPaths) : array
    {
        $betterReflection = new BetterReflection();
        $astLocator = ($betterReflection)->astLocator();

        $directoriesSourceLocator = new AggregateSourceLocator([
            new DirectoriesSourceLocator(\array_merge([$codePath], $dependenciesPaths), $astLocator),
            ($betterReflection)->sourceLocator(),
        ]);

        $reflector = new ClassReflector($directoriesSourceLocator);

        return \array_filter(\array_map(
            function (ReflectionClass $reflectionClass) use ($reflector, $codePath) : ?PHPClass {
                if (!str_starts_with($reflectionClass->getLocatedSource()->getFileName(), $codePath)) {
                    return null;
                }

                return new PHPClass($reflectionClass, $reflector);
            },
            $reflector->getAllClasses()
        ));
    }

    private function calendarVersions() : array
    {
        return $this->parameterBag()->get('aeon_php_calendar')['versions'];
    }

    private function calendarClasses(string $version) : array
    {
        return $this->loadCode(
            $this->parameterBag()->get('aeon_php_calendar'),
            $version
        );
    }

    private function calendarTwigVersions() : array
    {
        return $this->parameterBag()->get('aeon_php_calendar_twig')['versions'];
    }

    private function calendarTwigClasses(string $version) : array
    {
        return $this->loadCode(
            $this->parameterBag()->get('aeon_php_calendar_twig'),
            $version
        );
    }

    private function calendarDoctrineVersions() : array
    {
        return $this->parameterBag()->get('aeon_php_calendar_doctrine')['versions'];
    }

    private function calendarDoctrineClasses(string $version) : array
    {
        return $this->loadCode(
            $this->parameterBag()->get('aeon_php_calendar_doctrine'),
            $version
        );
    }


    private function calendarHolidaysClasses(string $version) : array
    {
        return $this->loadCode(
            $this->parameterBag()->get('aeon_php_calendar_holidays'),
            $version
        );
    }

    private function calendarHolidaysVersions() : array
    {
        return $this->parameterBag()->get('aeon_php_calendar_holidays')['versions'];
    }

    private function processClasses(string $version)
    {
        return $this->loadCode(
            $this->parameterBag()->get('aeon_php_process'),
            $version
        );
    }

    private function processVersions()
    {
        return $this->parameterBag()->get('aeon_php_process')['versions'];
    }

    private function retryClasses(string $version)
    {
        return $this->loadCode(
            $this->parameterBag()->get('aeon_php_retry'),
            $version
        );
    }

    private function retryVersions()
    {
        return $this->parameterBag()->get('aeon_php_retry')['versions'];
    }

    private function loadCode(array $library, string $version) : array
    {
        $codePath = $library['versions'][$version];

        $dependenciesPaths = [];
        if (isset($library['dependencies'])) {
            foreach ($library['dependencies'] as $dependency => $dependencyVersion) {
                $dependenciesPaths[] = $this->parameterBag()->get($dependency)['versions'][$dependencyVersion];
            }
        }

        return $this->codeClassesReflection($codePath, ...$dependenciesPaths);
    }

    abstract protected function parameterBag() : ParameterBagInterface;
}