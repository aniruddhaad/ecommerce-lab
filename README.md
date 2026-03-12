# E-Commerce Microservices Saga Architecture (Learning Project)

## Overview

This repository demonstrates a **microservices-based e‑commerce
workflow** built with:

-   PHP 8.3
-   Symfony 7
-   Symfony Messenger
-   RabbitMQ
-   PostgreSQL
-   Docker
-   Saga Orchestration Pattern

The goal of this project is to **learn and demonstrate distributed
system design**, event‑driven communication, and workflow orchestration
using the Saga pattern.

The architecture simulates a **checkout workflow** where multiple
services coordinate asynchronously.

------------------------------------------------------------------------

# Architecture Summary

Services in the system:

-   **Saga Service** -- Central workflow orchestrator
-   **Inventory Service** -- Handles inventory reservation
-   **Payment Service** -- Handles payment processing
-   **RabbitMQ** -- Message broker
-   **PostgreSQL** -- Service-specific databases

Each service:

-   runs independently
-   has its own database
-   communicates through RabbitMQ events and commands

------------------------------------------------------------------------

# Requirement Gathering

## Business Problem

In a distributed e‑commerce system, completing an order requires
multiple services:

1.  Reserve inventory
2.  Process payment
3.  Ship order

These services must coordinate **without using distributed database
transactions**.

Traditional ACID transactions do not work across microservices.

Therefore we implement the **Saga Pattern** to maintain consistency.

------------------------------------------------------------------------

# Functional Requirements

1.  A customer places an order.
2.  The system reserves inventory.
3.  The system processes payment.
4.  The order completes when all steps succeed.
5.  The system must handle failures and retries.
6.  Each service operates independently.
7.  Communication occurs via asynchronous messaging.

------------------------------------------------------------------------

# Quality Attributes

## Reliability

Messages must not be lost even if services crash.

RabbitMQ ensures: - message persistence - retries - delivery guarantees

## Scalability

Each service can scale independently.

Example:

Inventory service may run multiple workers.

## Loose Coupling

Services communicate only through:

-   **Commands**
-   **Events**

Services never call each other directly.

## Fault Tolerance

If a worker crashes:

-   RabbitMQ requeues messages
-   Workers can resume processing

## Observability

Logs allow tracing of Saga execution.

Example log:

\[SAGA order_id\] Inventory reserved

------------------------------------------------------------------------

# Actors / Users

## Customer

Initiates the checkout process.

## System Services

  Actor               Responsibility
  ------------------- ------------------------
  Saga Service        Orchestrates workflow
  Inventory Service   Reserves product stock
  Payment Service     Processes payments
  RabbitMQ            Message broker
  PostgreSQL          Data persistence

------------------------------------------------------------------------

# System Constraints

1.  Microservices must be **independent**
2.  Each service owns its **own database**
3.  No distributed transactions
4.  Communication must be **asynchronous**
5.  Services must be **idempotent**
6.  Messages may be **delivered multiple times**
7.  Workflow consistency maintained using **Saga orchestration**

------------------------------------------------------------------------

# Saga Pattern

The Saga pattern coordinates distributed transactions using:

-   **Commands**
-   **Events**
-   **State transitions**

Our project implements **Orchestrated Saga**.

The **Saga Service controls the workflow**.

------------------------------------------------------------------------

# High-Level Architecture

``` mermaid
flowchart LR

Customer --> Saga

Saga --> RabbitMQ
RabbitMQ --> InventoryService
RabbitMQ --> PaymentService

InventoryService --> RabbitMQ
PaymentService --> RabbitMQ

RabbitMQ --> Saga
```

------------------------------------------------------------------------

# Services

## Saga Service

Responsibilities:

-   Maintains Saga state
-   Handles workflow events
-   Dispatches commands

State Machine:

    STARTED
    RESERVING_INVENTORY
    PROCESSING_PAYMENT
    COMPLETED
    FAILED

------------------------------------------------------------------------

## Inventory Service

Handles:

-   ReserveInventory command
-   Emits InventoryReserved event

------------------------------------------------------------------------

## Payment Service

Handles:

-   ProcessPayment command
-   Emits PaymentProcessed event

------------------------------------------------------------------------

# Event-Driven Communication

Services communicate using **RabbitMQ exchanges and queues**.

Example message routing:

    Saga → ReserveInventory → Inventory Service
    Inventory → InventoryReserved → Saga
    Saga → ProcessPayment → Payment Service
    Payment → PaymentProcessed → Saga

------------------------------------------------------------------------

# Sequence Diagram

``` mermaid
sequenceDiagram

participant Customer
participant Saga
participant RabbitMQ
participant Inventory
participant Payment

Customer->>Saga: OrderCreated

Saga->>RabbitMQ: ReserveInventory
RabbitMQ->>Inventory: ReserveInventory

Inventory->>RabbitMQ: InventoryReserved
RabbitMQ->>Saga: InventoryReserved

Saga->>RabbitMQ: ProcessPayment
RabbitMQ->>Payment: ProcessPayment

Payment->>RabbitMQ: PaymentProcessed
RabbitMQ->>Saga: PaymentProcessed

Saga->>Saga: Complete Order
```

------------------------------------------------------------------------

# User Flow

``` mermaid
flowchart TD

Start --> OrderCreated
OrderCreated --> ReserveInventory
ReserveInventory --> InventoryReserved
InventoryReserved --> ProcessPayment
ProcessPayment --> PaymentProcessed
PaymentProcessed --> OrderCompleted
```

------------------------------------------------------------------------

# Saga State Transitions

``` mermaid
stateDiagram-v2

[*] --> STARTED
STARTED --> RESERVING_INVENTORY
RESERVING_INVENTORY --> PROCESSING_PAYMENT
PROCESSING_PAYMENT --> COMPLETED
PROCESSING_PAYMENT --> FAILED
```

------------------------------------------------------------------------

# Message Flow Diagram

``` mermaid
flowchart LR

Saga -->|Command| RabbitMQ
RabbitMQ --> Inventory
Inventory -->|Event| RabbitMQ
RabbitMQ --> Saga
Saga -->|Command| RabbitMQ
RabbitMQ --> Payment
Payment -->|Event| RabbitMQ
RabbitMQ --> Saga
```

------------------------------------------------------------------------

# What Has Been Implemented

Completed components:

-   Docker-based microservices environment
-   RabbitMQ messaging integration
-   Symfony Messenger workers
-   Saga orchestrator service
-   Inventory service
-   Payment service
-   Event-driven communication
-   Saga state machine
-   Idempotent message handling

------------------------------------------------------------------------

# Current Workflow

    OrderCreated
    ↓
    ReserveInventory
    ↓
    InventoryReserved
    ↓
    ProcessPayment
    ↓
    PaymentProcessed
    ↓
    Saga Completed

------------------------------------------------------------------------

# Future Roadmap

Next improvements planned:

## 1. Shipment Service

Add final fulfillment step.

    PaymentProcessed
    ↓
    CreateShipment
    ↓
    ShipmentCreated

## 2. Compensation Flow

Handle failures.

Example:

    PaymentFailed
    ↓
    ReleaseInventory
    ↓
    Saga Failed

## 3. Timeout Handling

Handle stuck workflows.

Example:

    Payment not completed in 5 minutes
    ↓
    Trigger compensation

## 4. Observability

Add:

-   structured logging
-   tracing
-   metrics

Tools:

-   Prometheus
-   Grafana
-   OpenTelemetry

## 5. CI/CD

Automate:

-   builds
-   tests
-   container deployments

------------------------------------------------------------------------

# Development Environment

Run services:

``` bash
docker compose up -d
```

Start workers:

    php bin/console messenger:consume saga
    php bin/console messenger:consume inventory
    php bin/console messenger:consume payment

RabbitMQ UI:

http://localhost:15672

------------------------------------------------------------------------

# Learning Goals

This project demonstrates:

-   Event-driven architecture
-   Microservices communication
-   Saga orchestration
-   Message broker reliability
-   Distributed workflow management

------------------------------------------------------------------------

# License

MIT License
