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
  const id = params.id as string;

  const [shop, setShop] = useState<Shop | null>(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState("");

  useEffect(() => {
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

    if (id) {
      fetchShop();
    }
  }, [id]);

  if (loading) {
    return <p>読み込み中...</p>;
  }

  if (error) {
    return <p>{error}</p>;
  }

  if (!shop) {
    return <p>店舗が見つかりません。</p>;
  }

  const imageSrc = shop.image_url ?? shop.image ?? "";

  return (
    <main>
      <div>
        <Link href="/">＜ 戻る</Link>

        <h1>{shop.name}</h1>

        {imageSrc && (
          <img
            src={imageSrc}
            alt={shop.name}
            style={{
              width: "100%",
              maxWidth: "600px",
              height: "360px",
              objectFit: "cover",
            }}
          />
        )}

        <p>
          #{shop.area?.name} #{shop.genre?.name}
        </p>

        <p>{shop.description}</p>
      </div>
    </main>
  );
}