<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Drug>
 */
class DrugFactory extends Factory
{
    public function definition(): array
    {
        $drugNames = [
            'باراسيتامول', 'أمبيكلين', 'ايبوبروفين', 'أسبرين', 'ديكلوفيناك',
            'أموكسيسيللين', 'أزيثرومايسين', 'كلاريثرومايسين', 'سيفالكسين', 'دوكسيسايكلين',
            'لوراتادين', 'كيتوتيفين', 'مونتلوكاست', 'ديسلوراتادين', 'فيكسوفينادين',
            'أومييرازول', 'رانتيدين', 'فاموتيدين', 'دومبيريدون', 'ميتوكلوبراميد',
            'سيمفاستاتين', 'أتورفاستاتين', 'كابتوبريل', 'إنالابريل', 'لوزارتان',
            'ميتوبرولول', 'أتينولول', 'فيراباميل', 'أملوديبين', 'نيفيديبين',
            'ميتفورمين', 'جليبنكلاميد', 'جليكلازيد', 'إنسولين', 'بيوجليتازون',
            'فيتامين د3', 'فيتامين ب12', 'حمض الفوليك', 'كالسيوم', 'حديد',
            'لوفاستاتين', 'وارفارين', 'هيبارين', 'كلوبيدوجريل', 'أسبرين كارديو'
        ];

        $scientificNames = [
            'Paracetamol', 'Ampicillin', 'Ibuprofen', 'Aspirin', 'Diclofenac',
            'Amoxicillin', 'Azithromycin', 'Clarithromycin', 'Cefalexin', 'Doxycycline',
            'Loratadine', 'Ketotifen', 'Montelukast', 'Desloratadine', 'Fexofenadine',
            'Omeprazole', 'Ranitidine', 'Famotidine', 'Domperidone', 'Metoclopramide',
            'Simvastatin', 'Atorvastatin', 'Captopril', 'Enalapril', 'Losartan',
            'Metoprolol', 'Atenolol', 'Verapamil', 'Amlodipine', 'Nifedipine',
            'Metformin', 'Glibenclamide', 'Gliclazide', 'Insulin', 'Pioglitazone',
            'Cholecalciferol', 'Cyanocobalamin', 'Folic Acid', 'Calcium Carbonate', 'Ferrous Sulfate'
        ];

        $manufacturers = [
            'شركة فاركو للأدوية', 'ممفيس للأدوية', 'الإسكندرية للأدوية',
            'أكتوبر فارما', 'شركة إيفا فارما', 'جلوبال نابي',
            'شركة سيديكو', 'كيميفا للصناعات الدوائية', 'فايزر مصر',
            'نوفارتيس مصر', 'جلاكسو سميث كلاين', 'سانوفي مصر',
            'رامكو للأدوية', 'أدكو للأدوية', 'مينا فارم'
        ];

        $categories = [
            'مسكنات الألم', 'مضادات حيوية', 'مضادات الحساسية', 'أدوية الجهاز الهضمي',
            'أدوية القلب والأوعية الدموية', 'أدوية السكر', 'فيتامينات ومكملات غذائية',
            'أدوية الجهاز التنفسي', 'أدوية المفاصل والعظام', 'أدوية الجلد',
            'أدوية الجهاز العصبي', 'أدوية النساء والولادة', 'أدوية الأطفال'
        ];

        $dosageForms = [
            'أقراص', 'كبسولات', 'شراب', 'حقن', 'مرهم', 'كريم',
            'قطرة', 'بخاخ', 'تحاميل', 'مسحوق', 'جل', 'لاصقة طبية'
        ];

        $descriptions = [
            'يستخدم لعلاج الألم والحمى',
            'مضاد حيوي واسع المفعول لعلاج العدوى البكتيرية',
            'مضاد للالتهابات ومسكن للألم',
            'يستخدم لعلاج أمراض القلب والأوعية الدموية',
            'يساعد في التحكم في مستوى السكر في الدم',
            'مكمل غذائي يساعد في تقوية الجهاز المناعي',
            'يستخدم لعلاج اضطرابات الجهاز الهضمي',
            'مضاد للهستامين لعلاج الحساسية',
            'يساعد في علاج ارتفاع ضغط الدم',
            'يستخدم لعلاج التهابات الجهاز التنفسي'
        ];

        return [
            'name' => $this->faker->randomElement($drugNames),
            'scientific_name' => $this->faker->randomElement($scientificNames),
            'description' => $this->faker->randomElement($descriptions),
            'manufacturer' => $this->faker->randomElement($manufacturers),
            'price' => $this->faker->randomFloat(2, 5, 300), // من 5 إلى 300 جنيه
            'insurance_covered' => $this->faker->boolean(70), // 70% محتمل يكون مغطى بالتأمين
            'category' => $this->faker->randomElement($categories),
            'dosage_form' => $this->faker->randomElement($dosageForms),
            'approval_status' => $this->faker->randomElement(['pending', 'approved', 'rejected']),
            'submitted_by_center_id' => $this->faker->optional(0.3)->numberBetween(1, 10), // 30% فقط مقترح من مراكز
            'is_government_approved' => $this->faker->boolean(80), // 80% معتمد حكومياً
            'is_active' => $this->faker->boolean(90), // 90% نشط
            'approved_at' => $this->faker->optional(0.8)->dateTimeThisYear(),
        ];
    }

    // State للأدوية المعتمدة حكومياً
    public function governmentApproved(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_government_approved' => true,
            'approval_status' => 'approved',
            'submitted_by_center_id' => null,
            'approved_at' => $this->faker->dateTimeThisYear(),
        ]);
    }

    // State للأدوية المقترحة من المراكز
    public function centerSubmitted(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_government_approved' => false,
            'approval_status' => $this->faker->randomElement(['pending', 'approved']),
            'submitted_by_center_id' => $this->faker->numberBetween(1, 10),
        ]);
    }

    // State للأدوية المرفوضة
    public function rejected(): static
    {
        return $this->state(fn (array $attributes) => [
            'approval_status' => 'rejected',
            'is_active' => false,
        ]);
    }
}
