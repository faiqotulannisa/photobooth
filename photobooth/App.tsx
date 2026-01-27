import { ImageWithFallback } from "@/app/components/figma/ImageWithFallback";

export default function App() {
  const photos = [
    "https://images.unsplash.com/photo-1656582117510-3a177bf866c3?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxoYXBweSUyMHBlcnNvbiUyMHBvcnRyYWl0fGVufDF8fHx8MTc2OTQ4MDAwMHww&ixlib=rb-4.1.0&q=80&w=1080&utm_source=figma&utm_medium=referral",
    "https://images.unsplash.com/photo-1607748862156-7c548e7e98f4?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxzbWlsaW5nJTIwZnJpZW5kc3xlbnwxfHx8fDE3Njk0ODAwMDB8MA&ixlib=rb-4.1.0&q=80&w=1080&utm_source=figma&utm_medium=referral",
    "https://images.unsplash.com/photo-1604452824399-0da61251ce3e?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxqb3lmdWwlMjBjZWxlYnJhdGlvbnxlbnwxfHx8fDE3Njk0ODAwMDF8MA&ixlib=rb-4.1.0&q=80&w=1080&utm_source=figma&utm_medium=referral",
  ];

  return (
    <div className="size-full flex items-center justify-center bg-gradient-to-br from-amber-50 to-orange-100 p-8">
      {/* Photo Booth Strip Container */}
      <div className="relative">
        {/* White border frame */}
        <div className="bg-white p-6 shadow-2xl rounded-sm">
          {/* Header text */}
          <div className="text-center mb-4 font-mono text-sm text-gray-600">
            <div className="border-b-2 border-dashed border-gray-300 pb-3">
              <p>★ PHOTO BOOTH ★</p>
              <p className="text-xs mt-1">Jan 27, 2026</p>
            </div>
          </div>

          {/* Three photo strips */}
          <div className="flex flex-col gap-4">
            {photos.map((photo, index) => (
              <div key={index} className="relative">
                {/* Individual photo frame */}
                <div className="border-4 border-white shadow-inner">
                  <div className="w-[320px] h-[240px] bg-gray-100">
                    <ImageWithFallback
                      src={photo}
                      alt={`Photo ${index + 1}`}
                      className="w-full h-full object-cover"
                    />
                  </div>
                </div>
              </div>
            ))}
          </div>

          {/* Footer */}
          <div className="text-center mt-4 font-mono text-xs text-gray-500 border-t-2 border-dashed border-gray-300 pt-3">
            <p>Keep this moment forever ♥</p>
          </div>
        </div>

        {/* Drop shadow effect */}
        <div className="absolute inset-0 -z-10 translate-x-3 translate-y-3 bg-black/20 blur-md rounded-sm"></div>
      </div>
    </div>
  );
}
