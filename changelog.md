# Changelog

## `1.7.0` – 2025-02-15

- Feature: PHP8.2 compatability

## `1.6.3` – 2024-01-12

- Fix: correct types passed to abs()

## `1.6.2` – 2023-05-19

- Fix: PHP8.0 compatabililty fixes

## `1.6.1` – 2022-10-05

- Fix: Add missing RFC5545 generator for London Christian Harmony singing

## `1.6.0` – 2022-10-05

- Feature: Add London Christian Harmony special formula

## `1.5.0` – 2022-01-06

- Feature: Update Whitsun bank holiday changes

## `1.4.1` – 2021-11-30

- Bugfix: fixed `Rule::getDates()` and `Rule::getDatesUntil()` for Easter-based cases where `$dtstart` is a formula date.

## `1.4.0` – 2021-10-16

- Feature: add `$rule['INTERVAL'] = N` support for events that occur every N years.
- Bugfix: fixed years for `Rule::getDates()` Easter-based cases where `$count > 1`.

## `1.3.2` – 2021-10-14

- Bugfix: fix times for Easter-related results for `Rule::getDates()` and `Rule::getDatesUntil()`

## `1.3.1` – 2021-09-29

- Bugfix: fix Easter-related results for `Rule::getDates()`

## `1.3.0` – 2021-07-03

- Feature: add `Rule::getDatesUntil()` method

## `1.2.3` – 2021-03-14

- Demo bugfix: allow end date to be null in title

## `1.2.2` – 2021-03-10

- Demo bugfix: handle date input error
- Demo bugfix: improve title date format

## `1.2.1` – 2021-03-09

- Fix backlink bug

## `1.2.0` – 2021-03-09

- Feature: add fifth Sunday rules
- Refactor demo

## `1.1.0` – 2021-03-07

- Feature: add exact-date special days
- Feature: output multiple special rules
- Non-breaking changes to rule output format

## `1.0.1` – 2021-01-09

- Remove non-required packages

## `1.0.0` – 2021-01-09

- Initial commit
