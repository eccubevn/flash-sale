# FlashSale

[![Build Status](https://travis-ci.org/eccubevn/flash-sale.svg?branch=master)](https://travis-ci.org/eccubevn/flash-sale)
[![Coverage Status](https://coveralls.io/repos/github/eccubevn/flash-sale/badge.png?branch=master)](https://coveralls.io/github/eccubevn/flash-sale?branch=master)

▼日本語訳
## 概要
ショップ運営者が期間限定セール（*FlashSale）を簡単に作成するために使うプラグインである。

*FlashSaleというのは電子商取引ストアが非常に短い期間で提供される割引販売イベント（プロモーション）である。数量は限られ、割引率も通常のプロモーションより高いため、非常に注目を集めやすいと思われる。

## Front-end
- 値引きの対象商品を表示する。
- 通常価格と値引き価格を表示する。
- カート及びショッピングページに値引きの合計金額を表示する。
- ご注文履歴及びメールに値引き価格を表示する。 
- 値引き後の金額に基づき、送料及びポイントが計算され、注文が成り立つ。 

## Back-end
- 複数のFlashsaleイベントを一覧に表示する。
- 自由にFlashSale期間を設定し、イベントを作成・更新する。 
- 商品、或いはカートに対して、値引きルール及びそのルールの詳細な条件を設定する。
- 値引きルールは2種ある：「商品」と「カート」
- 値引き条件はこの3つの対象に基づき、設定する：商品、カテゴリ、カートの合計金額
- 複数のルールと条件が設定可能。

## ライセンス
- LGPL-2.1

▼ENGLISH
## Overview 
A plugin that support the shop owner/website’s administrator to create and manage discount sales event calls FlashSale<sup>*</sup>
<small><sup>*</sup><em>FlashSale is a discount or promotion offered by an ecommerce store for a very short period of time. The quantity is limited, which often means the discounts are higher or more significant than ordinary promotions.</em></small>

## Front-end
- Display products which on flashsale campaign
- Display discount price together origin price of product
- Display discount amount on cart and shopping page. 
- Display discount price on mail and history page. 
- Order will be calculated with discount amount, delivery fee and point will base on discount price instead of original price. 

## Back-end
- Display all flashsale in store on grid view.
- Create/Edit a flashsale which specified period you want 
- Define the rule with condition and promotion for product, cart which want to be discounted.
- There are 2 rules of discount: product (class) and shopping cart
- There are 3 conditions of discount: product (class), category and cart total (order total)
- Multi rule and condition.

## License
- LGPL-2.1
