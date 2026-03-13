# Architecture Decisions

## ADR-002 – Saga Orchestration

**Decision:**
Distributed transactions are coordinated using the Saga pattern.

**Reason:**
Traditional database transactions cannot span multiple services.

**Alternative considered:**
Two-phase commit (2PC), which is complex and tightly coupled.
