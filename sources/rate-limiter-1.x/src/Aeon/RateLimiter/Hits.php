<?php

declare(strict_types=1);

namespace Aeon\RateLimiter;

use Aeon\Calendar\Gregorian\Calendar;
use Aeon\Calendar\Gregorian\DateTime;

/**
 * @psalm-immutable
 */
final class Hits implements \Countable
{
    /**
     * @var array<Hit>
     */
    private array $hits;

    public function __construct(Hit ...$hits)
    {
        $this->hits = $hits;
    }

    /**
     * @param array<int, array{id: string, datetime: string, ttl: string}> $data
     */
    public static function fromArray(array $data): self
    {
        $hits = [];

        foreach ($data as $hitData) {
            $hits[] = Hit::fromArray($hitData);
        }

        return new self(...$hits);
    }

    public function count(): int
    {
        return \count($this->hits);
    }

    public function oldest(): ?Hit
    {
        $oldest = null;

        foreach ($this->hits as $hit) {
            if (null === $oldest) {
                $oldest = $hit;

                continue;
            }

            if ($hit->isOlderThan($oldest)) {
                $oldest = $hit;
            }
        }

        return $oldest;
    }

    public function filterExpired(Calendar $calendar): self
    {
        return new self(...\array_filter($this->hits, fn (Hit $hit): bool => !$hit->expired($calendar)));
    }

    public function add(Hit $hit): self
    {
        return new self(...\array_merge($this->hits, [$hit]));
    }

    /**
     * @return array<int, array{id: string, datetime: string, ttl: string}>
     */
    public function normalize(): array
    {
        $hitsData = [];

        foreach ($this->hits as $hit) {
            $hitsData[] = $hit->normalize();
        }

        return $hitsData;
    }
}
