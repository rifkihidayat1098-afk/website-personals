<form method="GET" action="" class="search-form">
    <input type="text" name="q" placeholder="Cari data..." value="<?= htmlspecialchars($_GET['q'] ?? '') ?>" />
    <button type="submit">
        <div class="text-btn">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-search-icon lucide-search"><path d="m21 21-4.34-4.34"/><circle cx="11" cy="11" r="8"/></svg>    
            <span class="text">Cari</span>
        </div>
    </button>
</form>

<style>
.search-form {
    display: flex;
    gap: 10px;
    margin-bottom: 20px;
}

.search-form input {
    padding: 10px;
    font-size: 14px;
    border: 1px solid #ccc;
    border-radius: 6px;
    flex: 1;
    transition: border-color 0.3s;
}

.search-form input:focus {
    border-color: #2563eb;
    outline: none;
}

.search-form button {
    padding: 10px 20px;
    background-color: #435ebe;
    color: white;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    cursor: pointer;
    font-size: 14px;
    transition: background-color 0.3s;
}

.search-form button:hover {
    background-color:rgb(52, 71, 141);
}

svg {
    width: 15px;
}

.text {
    font-weight: 600
}

.text-btn {
    display: flex;
    align-items: center;
    gap: 5px
}

</style>
