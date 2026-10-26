<style>
    .main-navigation {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
        background: #111827;
        color: white;
        padding: 20px 30px;
        font-family: Arial, sans-serif;
    }

    .main-navigation .brand {
        color: white;
        text-decoration: none;
        font-size: 25px;
        font-weight: bold;
    }

    .main-navigation .right-menu {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
    }

    .main-navigation .links {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .main-navigation .links a {
        color: white;
        text-decoration: none;
        padding: 10px 14px;
        border-radius: 7px;
        font-size: 15px;
    }

    .main-navigation .links a:hover {
        background: #374151;
    }

    .user-menu {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-left: 10px;
    }

    .user-name {
        color: #d1d5db;
        font-size: 14px;
    }

    .logout-button {
        border: none;
        background: #dc2626;
        color: white;
        padding: 9px 14px;
        border-radius: 7px;
        cursor: pointer;
        font-size: 14px;
    }

    .logout-button:hover {
        background: #b91c1c;
    }

    @media (max-width: 900px) {
        .main-navigation {
            padding: 18px;
        }

        .right-menu {
            width: 100%;
        }
    }
</style>

<nav class="main-navigation">

    <a href="{{ url('/dashboard') }}" class="brand">
        📦 Sistem Inventaris
    </a>

    <div class="right-menu">

        <div class="links">
            <a href="{{ url('/dashboard') }}">Dashboard</a>
            <a href="{{ url('/barang') }}">Data Barang</a>
            <a href="{{ url('/transaksi') }}">Data Transaksi</a>
            <a href="{{ url('/laporan') }}">Laporan</a>
        </div>

        @auth
            <div class="user-menu">

                <span class="user-name">
                    👤 {{ Auth::user()->name }}
                </span>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="logout-button">
                        Logout
                    </button>
                </form>

            </div>
        @endauth

    </div>

</nav>