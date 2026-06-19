# Урок 07 — Корзина и checkout

**Цель:** полный коммерческий сценарий из ТЗ: несколько офферов в **корзине** → один **заказ** → одна **оплата** → отчёты по каждой позиции.

**Предусловие:** урок **03** («Купить сейчас», одна позиция) сдан — логику Paykeeper и Job переиспользуем.

## Задание (сдать наставнику)

- [ ] **Шаг 1:** миграции `carts`, `cart_items` (`profile_id`, `service_offer_id`, `input_type`, `input_value`).
- [ ] **Шаг 2:** «В корзину» с страницы оффера; страница `/cart` — список, удаление, итог.
- [ ] **Шаг 3:** checkout: `Cart` → `Order` + N `OrderItem` (snapshot цены) + `total_amount`; корзина очищается.
- [ ] **Шаг 4:** один `Payment` на весь заказ; webhook из урока 03 запускает Job **на каждый** `OrderItem`.
- [ ] **Шаг 5:** нельзя добавить оффер неактивной услуги; услуга без офферов — не добавляется.
- [ ] **Шаг 6:** тест: 2 позиции в корзине → 1 оплата → 2 набора `Report`.
- [ ] **Собес:** §19 (транзакция checkout), §47 (идемпотентность) — устно.
- [ ] `TIME_LOG`.

## Перед ДЗ

- [DOMAIN.md](../DOMAIN.md) — корзина и правило Offer
- Уроки **03–04** сданы

## Техника

### Шаг 1 — Cart (1:1 с Profile)

```php
// carts: profile_id UNIQUE
// Cart: belongsTo Profile; hasMany CartItem
// CartItem: belongsTo ServiceOffer; валидация input (vin/grz/sts)
```

`Cart::firstOrCreate(['profile_id' => $profile->id])` — **одна** корзина на профиль.

### Шаг 2 — Checkout в транзакции

```php
DB::transaction(function () use ($cart) {
    $order = Order::create([...]);
    foreach ($cart->items as $item) {
        $order->items()->create([
            'service_offer_id' => $item->service_offer_id,
            'input_type' => $item->input_type,
            'input_value' => $item->input_value,
            'price_snapshot' => $item->offer->price,
        ]);
    }
    $cart->items()->delete();
});
```

### Шаг 3 — Paykeeper

Сумма платежа = `$order->total_amount` (пересчёт при создании order из items).

**Критерий:** заказ с 2 VIN-пакетами (разные VIN) → 2×N отчётов после оплаты.

## UX (п.7 — зафиксировано)

- Основной путь: **«В корзину»** → `/cart` → checkout.
- **«Купить сейчас»** (если есть на UI): тот же код — `addToCart()` + redirect на `/cart` или checkout; **не** отдельный обход корзины.
- Убрать из прод-кода прямое создание `Order` из урока 03 (оставить только Cart → Order).

## Следующий урок

[08 — гараж](08-garage.md)

---

## Справочник

> [04 — тесты](04-feature-tests.md) — расширить тест корзины. [09 SQL](../../../homework/09-interview-sql.md).
