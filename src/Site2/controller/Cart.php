<?php
namespace Controller;

class Cart extends Main
{
    public function viewCart()
    {
        self::$View->cart = Session::Get('cart', []);
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
                    $cart = Session::Get('cart', []);
                    if (isset($cart[$gameId])) {
                        $cart[$gameId]['quantity']++;
                    } else {
                        $cart[$gameId] = [
                            'game' => $game,
                            'quantity' => 1
                        ];
                    }
                    Session::Set('cart', $cart);
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
            $cart = Session::Get('cart', []);
            if ($gameId && isset($cart[$gameId])) {
                unset($cart[$gameId]);
                Session::Set('cart', $cart);
            }
        }
        header('Location: /cart');
    }

    public function clearCart()
    {
        Session::Set('cart', []);
        header('Location: /cart');
    }

    public function checkout()
    {
        if (!Session::Exists('user_id')) {
            Session::Set('redirect_after_login', '/cart/checkout');
            header('Location: /login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Ici, vous pouvez ajouter la logique pour finaliser la commande
            // Par exemple, enregistrer la commande dans la base de données
            Session::Set('cart', []);
            Main::$View->message = 'Votre commande a été validée avec succès !';
        }

        Main::$View->title = 'Validation de la commande';
        Main::$View->Display('checkout');
    }

    public static function getCartItemCount()
    {
        $count = 0;
        $cart = Session::Get('cart', []);
        foreach ($cart as $item) {
            $count += $item['quantity'];
        }
        return $count;
    }
}
