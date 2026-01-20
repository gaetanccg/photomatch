<x-photographer-layout>
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Mon portfolio</h1>
                <p class="mt-1 text-sm text-gray-600">Gérez vos photos pour attirer plus de clients ({{ $images->count() }}/50)</p>
            </div>
        </div>

        <!-- Upload Section -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Ajouter des images</h2>

            <form action="{{ route('photographer.portfolio.store') }}" method="POST" enctype="multipart/form-data"
                  x-data="{ files: [], previews: [] }"
                  @change="
                    files = Array.from($event.target.files);
                    previews = [];
                    files.forEach(file => {
                        let reader = new FileReader();
                        reader.onload = e => previews.push(e.target.result);
                        reader.readAsDataURL(file);
                    });
                  ">
                @csrf

                <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-emerald-400 transition cursor-pointer"
                     onclick="document.getElementById('images').click()">
                    <input type="file" name="images[]" id="images" multiple accept="image/*" class="hidden">

                    <div x-show="previews.length === 0">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <p class="mt-2 text-sm text-gray-600">Cliquez ou déposez vos images ici</p>
                        <p class="mt-1 text-xs text-gray-500">JPEG, PNG, WEBP - Max 5 Mo par image - Max 10 images</p>
                    </div>

                    <!-- Preview -->
                    <div x-show="previews.length > 0" class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-5 gap-4">
                        <template x-for="(preview, index) in previews" :key="index">
                            <div class="relative aspect-square rounded-lg overflow-hidden">
                                <img :src="preview" class="w-full h-full object-cover">
                            </div>
                        </template>
                    </div>
                </div>

                <div x-show="previews.length > 0" class="mt-4 flex justify-end">
                    <x-button type="submit" loading-text="Upload en cours...">
                        Uploader <span x-text="previews.length"></span> image(s)
                    </x-button>
                </div>
            </form>

            @error('images')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
            @error('images.*')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Gallery -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Mes photos</h2>

            @if($images->count() > 0)
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                    @foreach($images as $image)
                        <div x-data="{ showMenu: false }" class="relative group">
                            <div class="aspect-square rounded-lg overflow-hidden bg-gray-100">
                                <img src="{{ $image->url }}" alt="{{ $image->caption ?? $image->original_name }}"
                                     class="w-full h-full object-cover transition group-hover:scale-105">

                                @if($image->is_featured)
                                    <div class="absolute top-2 left-2 bg-yellow-400 text-yellow-900 text-xs font-bold px-2 py-1 rounded">
                                        Vedette
                                    </div>
                                @endif
                            </div>

                            <!-- Actions Overlay -->
                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-40 transition flex items-center justify-center opacity-0 group-hover:opacity-100">
                                <button @click="showMenu = true" class="p-2 bg-white rounded-full shadow-lg hover:bg-gray-100 transition">
                                    <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                                    </svg>
                                </button>
                            </div>

                            <!-- Edit Modal -->
                            <div x-show="showMenu" x-cloak
                                 class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-50"
                                 @click.self="showMenu = false">
                                <div class="bg-white rounded-xl shadow-xl max-w-md w-full p-6" @click.stop>
                                    <div class="flex items-start justify-between mb-4">
                                        <h3 class="text-lg font-semibold text-gray-900">Modifier l'image</h3>
                                        <button @click="showMenu = false" class="text-gray-400 hover:text-gray-500">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                    </div>

                                    <img src="{{ $image->url }}" class="w-full h-48 object-cover rounded-lg mb-4">

                                    <form action="{{ route('photographer.portfolio.update', $image) }}" method="POST" class="space-y-4">
                                        @csrf
                                        @method('PUT')

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Légende</label>
                                            <input type="text" name="caption" value="{{ $image->caption }}"
                                                   class="w-full rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500"
                                                   placeholder="Description de la photo...">
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Spécialité</label>
                                            <select name="specialty_id" class="w-full rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500">
                                                <option value="">Aucune</option>
                                                @foreach($specialties as $specialty)
                                                    <option value="{{ $specialty->id }}" {{ $image->specialty_id == $specialty->id ? 'selected' : '' }}>
                                                        {{ $specialty->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="flex items-center">
                                            <input type="checkbox" name="is_featured" id="is_featured_{{ $image->id }}" value="1"
                                                   {{ $image->is_featured ? 'checked' : '' }}
                                                   class="h-4 w-4 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500">
                                            <label for="is_featured_{{ $image->id }}" class="ml-2 text-sm text-gray-700">
                                                Photo vedette (affichée en priorité)
                                            </label>
                                        </div>

                                        <div class="flex justify-between pt-4 border-t">
                                            <form action="{{ route('photographer.portfolio.destroy', $image) }}" method="POST"
                                                  onsubmit="return confirm('Supprimer cette image ?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-700 text-sm font-medium">
                                                    Supprimer
                                                </button>
                                            </form>
                                            <x-button type="submit" size="sm">Enregistrer</x-button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            @if($image->caption)
                                <p class="mt-2 text-xs text-gray-500 truncate">{{ $image->caption }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Aucune photo</h3>
                    <p class="mt-1 text-sm text-gray-500">Commencez à construire votre portfolio en ajoutant des images.</p>
                </div>
            @endif
        </div>
    </div>
</x-photographer-layout>
