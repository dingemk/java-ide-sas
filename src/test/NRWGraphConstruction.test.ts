import { expect, it } from 'vitest';
import { NRWGraphClass } from '../compiler/java/runtime/modules/nrw/NRWGraphClass';
import { NRWEdgeClass } from '../compiler/java/runtime/modules/nrw/NRWEdgeClass';
import { NRWVertexClass } from '../compiler/java/runtime/modules/nrw/NRWVertexClass';
import { StringClass } from '../compiler/java/runtime/system/javalang/ObjectClassStringClass';

it('returns graph and edge instances from native constructors used by browser code', () => {
    const graph = new NRWGraphClass();
    expect(graph._constructor1()).toBe(graph);
    const a = new NRWVertexClass()._constructor1(new StringClass('A'));
    const b = new NRWVertexClass()._constructor1(new StringClass('B'));
    graph._addVertex(a);
    graph._addVertex(b);
    const edge = new NRWEdgeClass();
    expect(edge._constructor1(a, b, 3)).toBe(edge);
    graph._addEdge(edge);
    expect(graph._getEdge(a, b)).toBe(edge);
    expect(edge._getWeight()).toBe(3);
});
