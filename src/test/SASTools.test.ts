import { afterEach, describe, expect, it, vi } from "vitest";
import { SASToolsClass } from "../compiler/java/runtime/graphics/sas/SASToolsClass";
import { IllegalArgumentExceptionClass } from "../compiler/java/runtime/system/javalang/IllegalArgumentException";

afterEach(() => vi.useRealTimers());

describe("Tools local time", () => {
    it.each([[0, 0, 0], [12, 34, 56], [23, 59, 59]])("returns local time %s:%s:%s", (hour, minute, second) => {
        vi.useFakeTimers();
        vi.setSystemTime(new Date(2026, 9, 7, hour, minute, second));
        expect(SASToolsClass._getHour()).toBe(hour);
        expect(SASToolsClass._getMinute()).toBe(minute);
        expect(SASToolsClass._getSecond()).toBe(second);
    });
    it("reads the current time again on each call", () => {
        vi.useFakeTimers();
        vi.setSystemTime(new Date(2026, 9, 7, 23, 59, 59));
        vi.advanceTimersByTime(1000);
        expect(SASToolsClass._getHour()).toBe(0);
        expect(SASToolsClass._getMinute()).toBe(0);
        expect(SASToolsClass._getSecond()).toBe(0);
    });
});

describe("Tools.stringToDouble", () => {
    it.each([["3.14", 3.14], [" -2.5 ", -2.5], ["1e3", 1000], [".5D", 0.5], ["0x1.8p2", 6], ["Infinity", Infinity]])("parses %s", (value, expected) => {
        expect(SASToolsClass._stringToDouble(value as string)).toBe(expected);
    });
    it("supports NaN and negative zero", () => {
        expect(SASToolsClass._stringToDouble("NaN")).toBeNaN();
        expect(Object.is(SASToolsClass._stringToDouble("-0"), -0)).toBe(true);
    });
    it.each(["", "3,14", "12abc", "1 2"])("rejects %s instead of parsing a prefix", value => {
        expect(() => SASToolsClass._stringToDouble(value)).toThrow(IllegalArgumentExceptionClass);
    });
});
