<?php
namespace Controller;

class Cart extends Main
{
    public function viewCart()
    {
        self::$View->cart = $_SESSION['cart'] ?? [];
        self::$View->title = 'Panier';
        self::$View->Display('cart');
    }

    public function addToCart()
    {
        $response = ['success' => false, 'cartCount' => 0];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $gameId = $_POST['game_id'] ?? null;
            if ($gameId) {
                $gameModel = new \Model\Game();
                $game = $gameModel->getGame($gameId);
                if ($game) {
                    if (!isset($_SESSION['cart'])) {
                        $_SESSION['cart'] = [];
                    }
                    if (isset($_SESSION['cart'][$gameId])) {
                        $_SESSION['cart'][$gameId]['quantity']++;
                    } else {
                        $_SESSION['cart'][$gameId] = [
                            'game' => $game,
                            'quantity' => 1
                        ];
                    }
                    $response['success'] = true;
                    $response['cartCount'] = self::getCartItemCount();
                }
            }
        }

        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }

    public function removeFromCart()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $gameId = $_POST['game_id'] ?? null;
            if ($gameId && isset($_SESSION['cart'][$gameId])) {
                unset($_SESSION['cart'][$gameId]);
            }
        }
        header('Location: /cart');
    }

    public function clearCart()
    {
        $_SESSION['cart'] = [];
        header('Location: /cart');
    }

    public function checkout()
    {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['redirect_after_login'] = '/cart/checkout';
            header('Location: /login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Ici, vous pouvez ajouter la logique pour finaliser la commande
            // Par exemple, enregistrer la commande dans la base de données
            $_SESSION['cart'] = [];
            Main::$View->message = 'Votre commande a été validée avec succès !';
        }

        Main::$View->title = 'Validation de la commande';
        Main::$View->Display('checkout');
    }

    public static function getCartItemCount()
    {
        $count = 0;
        if (isset($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $item) {
                $count += $item['quantity'];
            }
        }
        return $count;
    }
}
