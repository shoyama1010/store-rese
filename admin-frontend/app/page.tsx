"use client";

import { useEffect, useMemo, useState } from "react";
import { api } from "@/lib/api";

type Shop = {
  id: number;
  name: string;
  area?: { name: string };
  genre?: { name: string };
  image_url?: string;
  image?: string;
};

export default function HomePage() {
  const [shops, setShops] = useState<Shop[]>([]);
  const [keyword, setKeyword] = useState("");
  const [area, setArea] = useState("all");
  const [genre, setGenre] = useState("all");
  const [sort, setSort] = useState("random");
  const [menuOpen, setMenuOpen] = useState(false);
  const [isLoggedIn, setIsLoggedIn] = useState(false);

  useEffect(() => {
    const fetchShops = async () => {
      const data = await api("/api/shops");
      setShops(data.shops ?? []);
    };

    fetchShops();
  }, []);

  const areas = [
    ...new Set(shops.map((shop) => shop.area?.name).filter(Boolean)),
  ];

  const genres = [
    ...new Set(shops.map((shop) => shop.genre?.name).filter(Boolean)),
  ];

  const filteredShops = useMemo(() => {
    let result = [...shops];

    if (area !== "all") {
      result = result.filter((shop) => shop.area?.name === area);
    }

    if (genre !== "all") {
      result = result.filter((shop) => shop.genre?.name === genre);
    }

    if (keyword) {
      result = result.filter((shop) =>
        shop.name.toLowerCase().includes(keyword.toLowerCase())
      );
    }

    if (sort === "name") {
      result.sort((a, b) => a.name.localeCompare(b.name, "ja"));
    }

    if (sort === "reverse") {
      result.sort((a, b) => b.name.localeCompare(a.name, "ja"));
    }

    if (sort === "random") {
      result.sort(() => Math.random() - 0.5);
    }

    return result;
  }, [shops, area, genre, keyword, sort]);

  return (
    <main className="min-h-screen bg-[#eeeeee] px-6 py-8">

      {menuOpen && (
        <div className="fixed inset-0 z-50 bg-white">
          <button
            type="button"
            onClick={() => setMenuOpen(false)}
            className="absolute left-[100px] top-[105px] flex h-10 w-10 items-center justify-center rounded bg-[#305dff] text-2xl text-white shadow-md"
          >
            ×
          </button>

          <nav className="flex h-screen flex-col items-center justify-center gap-6 text-3xl text-[#305dff]">
            <button onClick={() => setMenuOpen(false)}>Home</button>

            {isLoggedIn ? (
              <>
                <button
                  onClick={() => {
                    setIsLoggedIn(false);
                    setMenuOpen(false);
                  }}
                >
                  Logout
                </button>
                <a href="http://localhost/mypage">Mypage</a>
              </>
            ) : (
              <>
                <a href="http://localhost/register">Registration</a>
                <a href="http://localhost/login">Login</a>
              </>
            )}

            <a href="http://localhost/multi/login">Multi-Login</a>
          </nav>
        </div>
      )}

      {/* <header className="mx-auto flex max-w-[1160px] items-center justify-between"> */}
      <header className="mx-auto flex max-w-[1230px] items-center justify-between">
        <div className="flex items-center gap-4">
          {/* <button className="flex h-9 w-9 items-center justify-center rounded bg-[#305dff] text-white shadow-md">
            ≡
          </button> */}
          <button 
            type="button"
            onClick={() => setMenuOpen(true)}
          className="flex h-10 w-10 items-center justify-center rounded bg-[#305dff] text-white shadow-md">
            ☰
          </button>

          <h1 className="text-3xl font-bold text-[#305dff]">Rese</h1>
        </div>

        <div className="flex h-11 w-[540px] items-center rounded bg-white shadow-md">
          <select
            value={area}
            onChange={(e) => setArea(e.target.value)}
            className="h-full w-[120px] border-r px-3 text-sm outline-none"
          >
            <option value="all">All area</option>
            {areas.map((area) => (
              <option key={area} value={area}>
                {area}
              </option>
            ))}
          </select>

          <select
            value={genre}
            onChange={(e) => setGenre(e.target.value)}
            className="h-full w-[120px] border-r px-3 text-sm outline-none"
          >
            <option value="all">All genre</option>
            {genres.map((genre) => (
              <option key={genre} value={genre}>
                {genre}
              </option>
            ))}
          </select>

          <div className="flex flex-1 items-center px-3">
            <span className="mr-2 text-gray-300">⌕</span>
            <input
              placeholder="Search ..."
              value={keyword}
              onChange={(e) => setKeyword(e.target.value)}
              className="w-full text-sm outline-none"
            />
          </div>
        </div>
      </header>

      <div className="mx-auto mt-8 flex max-w-[1160px] justify-center">
        <label className="mr-3 text-base">並び替え：</label>
        <select
          value={sort}
          onChange={(e) => setSort(e.target.value)}
          className="h-9 w-[130px] rounded border border-gray-300 bg-white px-3 outline-none"
        >
          <option value="random">ランダム</option>
          <option value="name">名前順</option>
          <option value="reverse">名前逆順</option>
        </select>
      </div>

      <section className="mx-auto mt-8 grid max-w-[1160px] grid-cols-1 gap-7 sm:grid-cols-2 lg:grid-cols-4">
        {filteredShops.map((shop) => (
          <article
            key={shop.id}
            // className="overflow-hidden rounded bg-white shadow-md"
            className="overflow-hidden rounded bg-white shadow"
          >
            <img
              src={shop.image_url ?? shop.image ?? "/no-image.png"}
              alt={shop.name}
              // className="h-[135px] w-full object-cover"
              className="h-[120px] w-full object-cover"
            />

            <div className="p-5">
              <h2 className="mb-1 text-lg font-bold">{shop.name}</h2>

              <p className="mb-3 text-sm">
                #{shop.area?.name} #{shop.genre?.name}
              </p>

              <button className="rounded bg-[#305dff] px-4 py-1.5 text-sm text-white">
              {/* <button className="rounded-md bg-[#305dff] px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700 transition"> */}
                詳しくみる
              </button>
            </div>
          </article>
        ))}
      </section>
    </main >
  );
}