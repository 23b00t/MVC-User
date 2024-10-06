<?php 
require_once __DIR__ . '/../../helpers/Helper.php';
Helper::validateSession();
$csrf_token = Helper::generateCSRFToken(); 

// @var controllers\PurchaseController $purchase
// @var controllers\CardController $cards
?>

<?php $pageTitle = 'Bestellung'; require __DIR__ . '/../head.php'; ?>
<div class="container">
    <h1>Bestellung #<?= htmlspecialchars($purchase->id()); ?></h1>
    <div class="purchase-details">
        <p><strong>Bestelldatum:</strong> <?= htmlspecialchars($purchase->purchased_at()); ?></p>
        <p><strong>Status:</strong> <?= htmlspecialchars($purchase->status()); ?></p>
    </div>

    <h2>Pizzen in der Bestellung:</h2>
    <ul>
        <?php foreach ($cards as $cardItem): ?>
            <li>
                <?php $cardItem = unserialize($cardItem); ?>
                <!-- TODO: refactoring and add link to Pizza#show -->
                <strong><?= htmlspecialchars(Pizza::findBy($cardItem->pizza_id(), 'id')->name()); ?></strong> 
                (Anzahl: 
                <form action="./index.php?card/update/<?= htmlspecialchars($cardItem->id()); ?>" method="POST" style="display:inline;">
                    <input type="number" name="quantity" value="<?= htmlspecialchars($cardItem->quantity()); ?>" min="1" required style="width: 60px;">
                    <!-- insert csrf_token -->
                    <input type="hidden" name="csrf_token" value="<?= $csrf_token; ?>">
                    <button type="submit" class="btn btn-sm btn-primary">Ändern</button>
                </form>
                )
                <a href="./index.php?card/delete/<?= htmlspecialchars($cardItem->id()); ?>" class="btn btn-danger btn-sm">Entfernen</a>
            </li>
        <?php endforeach; ?>
    </ul>

    <div class="purchase-actions">
        <a href="./index.php?purchase/edit/<?= htmlspecialchars($purchase->id()); ?>" class="btn btn-success">Bestellung tätigen</a>
        <a href="./index.php?purchase/delete/<?= htmlspecialchars($purchase->id()); ?>" class="btn btn-danger">Bestellung verwerfen</a>
    </div>

    <a href="./index.php?purchase/index" class="button">Zurück zur Übersicht</a>
</div>
<?php require __DIR__ . '/../tail.php'; ?>
