package uwb.licencjat.service;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.data.domain.Sort;
import org.springframework.stereotype.Service;
import org.springframework.validation.BindingResult;
import uwb.licencjat.exception.ClinicCategoryNotFoundException;
import uwb.licencjat.model.ClinicCategory;
import uwb.licencjat.model.User;
import uwb.licencjat.repository.ClinicCategoryRepository;

import java.util.List;
import java.util.Optional;

@Service
public class ClinicCategoryService {

    private ClinicCategoryRepository clinicCategoryRepository;

    @Autowired
    public ClinicCategoryService(ClinicCategoryRepository clinicCategoryRepository) {
        this.clinicCategoryRepository = clinicCategoryRepository;
    }

    public List<ClinicCategory> getAllByValueSorted(String value, String sortBy){
        Sort sort;
        if(sortBy.equals("desc"))
            sort = Sort.by(Sort.Direction.DESC, "name");
        else
            sort = Sort.by(Sort.Direction.ASC, "name");
        return clinicCategoryRepository.findAllByNameDescriptionContaining(value,value,sort);
    }

    public List<ClinicCategory> getAllCategories(){
        return clinicCategoryRepository.findAll();
    }

    public void saveClinicCategory(ClinicCategory clinicCategory){
        clinicCategoryRepository.save(clinicCategory);
    }

    public ClinicCategory findById(long id){
        return checkIsPresent(clinicCategoryRepository.findById(id));
    }

    public ClinicCategory findByName(String name){
        return checkIsPresent(clinicCategoryRepository.findByNameIgnoreCase(name));
    }

    private ClinicCategory checkIsPresent(Optional<ClinicCategory> clinicCategoryOptional){
        if(clinicCategoryOptional.isPresent()){
            return clinicCategoryOptional.get();
        }else{
            throw new ClinicCategoryNotFoundException();
        }
    }

    public boolean existById(long id) {
        return clinicCategoryRepository.existsById(id);
    }

    public void deleteById(long id){
        clinicCategoryRepository.deleteById(id);
    }

    public void checkNameUnique(long id, ClinicCategory clinicCategory, BindingResult result){
        Optional<ClinicCategory> clinicCategoryFromDb =
                clinicCategoryRepository.findByNameIgnoreCase(clinicCategory.getName());
        if(clinicCategoryFromDb.isPresent()){
            if(clinicCategoryFromDb.get().getId()!=id)
                result.rejectValue("name","UniqueName", "Kategoria o takiej nazwie jest już w bazie");
        }
    }

    public void checkNameUnique(ClinicCategory clinicCategory, BindingResult result){
        if(clinicCategoryRepository.existsByNameIgnoreCase(clinicCategory.getName()))
            result.rejectValue("name","UniqueName", "Kategoria o takiej nazwie jest już w bazie");
    }

}
