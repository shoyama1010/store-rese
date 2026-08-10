"use client";

import { useEffect, useState } from "react";
import { useParams } from "next/navigation";
import Link from "next/link";
import { api } from "@/lib/api";

type Shop = {
  id: number;
  name: string;
  description: string;
  image_url?: string;
  image?: string;
  area?: {
    id: number;
    name: string;
  };
  genre?: {
    id: number;
    name: string;
  };
};

export default function ShopDetailPage() {
  const params = useParams();

  const id = Array.isArray(params.id)
    ? params.id[0]
    : params.id;

  const [shop, setShop] = useState<Shop | null>(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState("");

  const [date, setDate] = useState("");
  const [time, setTime] = useState("17:00");
  const [number, setNumber] = useState("1");

  useEffect(() => {
    if (!id) return;

    const fetchShop = async () => {
      try {
        const data = await api(`/api/shops/${id}`);
        setShop(data.shop);
      } catch (err) {
        console.error(err);
        setError("店舗情報の取得に失敗しました。");
      } finally {
        setLoading(false);
      }
    };

    fetchShop();
  }, [id]);

  if (loading) {
    return (
      <main className="min-h-screen bg-[#eeeeee] p-8">
        <p>読み込み中...</p>
      </main>
    );
  }

  if (error) {
    return (
      <main className="min-h-screen bg-[#eeeeee] p-8">
        <p>{error}</p>
      </main>
    );
  }

  if (!shop) {
    return (
      <main className="min-h-screen bg-[#eeeeee] p-8">
        <p>店舗が見つかりません。</p>
      </main>
    );
  }

  const imageSrc =
    shop.image_url ??
    shop.image ??
    "/no-image.png";

  return (
    <main className="min-h-screen bg-[#eeeeee]">
      {/* ヘッダー */}
      <header className="mx-auto flex max-w-[1160px] items-center px-4 py-8">
        <Link
          href="/"
          className="mr-4 flex h-10 w-10 items-center justify-center rounded-md bg-[#305dff] text-xl text-white shadow"
        >
          ≡
        </Link>

        <h1 className="text-3xl font-bold text-[#305dff]">
          RSV
        </h1>
      </header>

      {/* メイン */}
      <section className="mx-auto grid max-w-[1160px] grid-cols-1 gap-12 px-4 pb-16 lg:grid-cols-2">
        {/* 左側 */}
        <div>
          <div className="mb-6 flex items-center gap-4">
            <Link
              href="/"
              className="flex h-10 w-10 items-center justify-center rounded-md bg-white text-xl shadow"
            >
              ＜
            </Link>

            <h2 className="text-3xl font-bold">
              {shop.name}
            </h2>
          </div>

          <img
            src={imageSrc}
            alt={shop.name}
            className="h-[360px] w-full object-cover"
          />

          <p className="mt-8">
            #{shop.area?.name} #{shop.genre?.name}
          </p>

          <p className="mt-8 leading-8">
            {shop.description}
          </p>
        </div>

        {/* 右側 */}
        <div>
          <div className="relative min-h-[620px] overflow-hidden rounded-md bg-[#305dff] shadow-lg">
            <div className="p-8">
              <h2 className="mb-8 text-2xl font-bold text-white">
                予約
              </h2>

              {/* 日付 */}
              <input
                type="date"
                value={date}
                onChange={(e) => setDate(e.target.value)}
                className="mb-4 h-10 w-[160px] rounded bg-white px-3"
              />

              {/* 時間 */}
              <select
                value={time}
                onChange={(e) => setTime(e.target.value)}
                className="mb-4 h-10 w-full rounded bg-white px-3"
              >
                {[
                  "11:00",
                  "12:00",
                  "13:00",
                  "14:00",
                  "15:00",
                  "16:00",
                  "17:00",
                  "18:00",
                  "19:00",
                  "20:00",
                  "21:00",
                  "22:00",
                ].map((value) => (
                  <option key={value} value={value}>
                    {value}
                  </option>
                ))}
              </select>

              {/* 人数 */}
              <select
                value={number}
                onChange={(e) => setNumber(e.target.value)}
                className="mb-6 h-10 w-full rounded bg-white px-3"
              >
                {Array.from({ length: 10 }, (_, index) => {
                  const value = String(index + 1);

                  return (
                    <option key={value} value={value}>
                      {value}人
                    </option>
                  );
                })}
              </select>

              {/* 入力確認 */}
              <div className="rounded-md bg-[#4f7cff] p-5 text-white">
                <div className="grid grid-cols-[90px_1fr] gap-y-3">
                  <span>Shop</span>
                  <span>{shop.name}</span>

                  <span>Date</span>
                  <span>{date || "未選択"}</span>

                  <span>Time</span>
                  <span>{time}</span>

                  <span>Number</span>
                  <span>{number}人</span>
                </div>
              </div>
            </div>

            <button
              type="button"
              className="absolute bottom-0 left-0 w-full bg-[#0038ff] py-5 font-bold text-white"
              onClick={() => {
                alert("予約APIは次のステップで実装します。");
              }}
            >
              予約する
            </button>
          </div>
        </div>
      </section>
    </main>
  );
}