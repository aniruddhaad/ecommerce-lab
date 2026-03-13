# Architecture Decisions

## ADR-001 – Event Driven Communication

**Decision:**
Services communicate asynchronously using RabbitMQ events.

**Reason:**
Loose coupling between services and better scalability.

**Alternative considered:**
Direct HTTP calls between services.